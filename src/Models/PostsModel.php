<?php

namespace App\Models;

use App\Utils\DB;

class PostsModel
{
    private $db;

    public function __construct()
    {
        $this->db = DB::Connection();
    }

    public function getPostById($id)
    {
        $query = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetchAll();
    }

    public function getAllPosts()
    {
        $query = "SELECT * FROM posts";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPost(string $author, string $contents)
    {
        $query = "INSERT INTO posts (author, contents) VALUES (:author, :contents)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'author' => $author,
            'contents' => $contents
        ]);

        return $this->db->lastInsertId();
    }

    public function deletePost(int $id)
    {
        $query = "DELETE FROM posts WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return 1;
    }

    public function isPostExists(int $id)
    {
        $query = "select if(exists(select 1 from posts where id = :id), 1, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return array_values($stmt->fetch())[0];
    }

    public function updatePost(int $id, string $contents)
    {
        $query = "UPDATE posts SET contents = :contents WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'id' => $id,
            'contents' => $contents
        ]);

        return $this->getPostById($id);
    }
}