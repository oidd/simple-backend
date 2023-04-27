# simple-backend
Простая реализация backend'а на языке PHP. 

Точкой входа является файл `public/index.php` – в этот файл следует перенаправлять каждый входящий запрос к серверу.
## Маршрутизация
Центральным элементом является класс `Router`. Его статические методы `getReq()` и `getRes()` возвращают объекты классов `Request` и `Response`, которые создаются в момент, когда приходит запрос.
Для определения эндпоинта необходимо использовать статический метод `add()` класса `Router`.
```PHP
Router::add(
    ['patch'],
    '/posts/{id}',
    [\App\Controllers\PostsController::class, 'updatePost'],
    [
        'where' => [
            'id' => '[0-9]+'
        ],
        'middleware' => ['auth']
    ]
);
```
* `$method` – массив доступных HTTP-методов для данного эндпоинта. 

* `$route` – это путь, по которому будет осуществляться доступ к данному эндпоинту.
Существует возможность передать URI-параметр – для этого необходимо обернуть его в фигурные скобки.

* `$callback` – функция для этого эндпоинта. Если были заданы URI-параметры, они будут переданы в порядке следования.

* `$settings` – необязательный аргумент, массив, некоторых параметров. 
Параметр `'where'` определяет регулярные выражения для URI-параметра. По умолчанию все URI-параметры имеют вид `[A-Za-z0-9]+`. 
Параметр `'middleware'` определяет функции, что будут выполняться перед обработкой запроса. Эти функции должны быть определены в файле `src/Utils/Routing/Middleware/Middleware.php`.

## Request и Response
Экземпляры этих классов доступны из любой точки программы через статические методы `getReq()` и `getRes()` класса `Router`. 

Объект `Request` хранит в себе информацию о запросе – заголовки, HTTP-метод, URI и тело запроса. Метод `getBody()` вернет тело запроса в формате строки, как оно было передано. Метод `getJSONBody()` распарсит json-документ, хранящийся в теле запроса. Если распарсить не удастся, вернет клиенту ошибку `400`.

Через объект `Response` задается код ответа HTTP (`sendHTTPCode()`) и другие заголовки ответа (`setHeader()`). Также через этот запрос задается тело ответа – метод `sendJSON()` сформирует ответ в JSON-формате.

```PHP
public static function createPost()
{
  if (($c = Router::getReq()->getJSONBody()["contents"]) === NULL)
    throw new \Exception("Request body should contain 'contents' field", 400);

  $model = new PostsModel();

  Router::getRes()->sendJSON($model->createPost(Authorization::$userName, $c));
}
```

## Middleware
Выполняются перед обработкой запроса. Существуют глобальные – задаются в поле `$globalMiddleware` в классе `App\Utils\Routing\Middleware`, и для конкретного запроса – задается в четвертом аргументе метода `Route::add()`. Каждый класс должен имплементировать интерфейс `IMiddleware` и задаваться в поле `$registeredMiddleware` класса `App\Utils\Routing\Middleware`.

## Authorization
В классе `Utils\Authorization` реализованы методы для работы с библиотекой `Firebase\JWT`. В middlewar'e `Utils\Routing\Middleware\Auth` происходит проверка на корректность переданного токена. В контроллере `Controllers\AuthController` реализована возможность регистрации и входа. Эндпоинт `/login/` возвратит пользователю токен, если тот передаст в теле запроса логин и пароль.

## Обработка ошибок и исключений
Реализована в классе `Utils\Errors`. Статический метод `exceptionHandler()` вызывается при появлении необработанного исключения. В нем происходит вызов метода `errorLog()`, а также отправка клиенту информации об ошибке.

При возникновении фатальной ошибки, метод `fatalErrorHandler()` обработает ее схожим образом – вызовет метод `errorLog()` и отправит пользователю сообщение о внутренней ошибке сервера `500`.

С помощью метода `errorLog()` ведется логирование ошибок. Файлы с логами будут создаваться в папке, указанной в классе `Config`.
