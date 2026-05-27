<?php

class UserRepository {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create($data)
    {
        $query = "
            INSERT INTO users (
                name,
                email,
                password
            )
            VALUES (
                :name,
                :email,
                :password
            )
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ":name"=>$data['name'],
            ":email"=>$data['email'],
            ":password"=>$data['password']
        ]);
    }


    
    public function getAll()
    {
        $query="
            SELECT
                id,
                name,
                email,
                role,
                created_at
            FROM users
            ORDER BY id
        ";

        $stmt=$this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function findById($id)
    {
        $query="
            SELECT
                id,
                name,
                email,
                created_at
            FROM users
            WHERE id=:id
            LIMIT 1
        ";

        $stmt=$this->conn->prepare($query);

        $stmt->execute([
            ":id"=>$id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    
    public function findByEmail($email)
    {
        $query="
            SELECT *
            FROM users
            WHERE email=:email
            LIMIT 1
        ";

        $stmt=$this->conn->prepare($query);

        $stmt->execute([
            ":email"=>$email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    
    public function update($id,$data)
    {
        $query="
            UPDATE users
            SET
                name=:name,
                email=:email
            WHERE id=:id
        ";

        $stmt=$this->conn->prepare($query);

        return $stmt->execute([
            ":name"=>$data['name'],
            ":email"=>$data['email'],
            ":id"=>$id
        ]);
    }


    
    public function updatePassword(
        $id,
        $hashedPassword
    )
    {

        $query="
            UPDATE users
            SET password=:password
            WHERE id=:id
        ";

        $stmt=$this->conn->prepare($query);

        return $stmt->execute([
            ":password"=>$hashedPassword,
            ":id"=>$id
        ]);
    }


    
    public function delete($id)
    {
        $query="
            DELETE FROM users
            WHERE id=:id
        ";

        $stmt=$this->conn->prepare($query);

        return $stmt->execute([
            ":id"=>$id
        ]);
    }


    
    public function verifyCredentials(
        $email
    )
    {
        $query="
            SELECT *
            FROM users
            WHERE email=:email
            LIMIT 1
        ";

        $stmt=$this->conn->prepare($query);

        $stmt->execute([
            ":email"=>$email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    
    public function exists($id)
    {
        $query="
            SELECT COUNT(*)
            FROM users
            WHERE id=:id
        ";

        $stmt=$this->conn->prepare($query);

        $stmt->execute([
            ":id"=>$id
        ]);

        return $stmt->fetchColumn()>0;
    }

}