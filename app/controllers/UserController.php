<?php

include_once 'services/database/DBConnector.php';

class UserController
{
    public function __construct()
    {
        $this->dbConnector = new DBConnector();
    }

    public function createUser($request)
    {
        $all = $request->getBody();

        if($this->dbConnector->conn->connect_errno) {
            return json_encode([
                "status" => "error",
                "errorMessage" => "Error while connecting to MySQL: (" . $this->dbConnector->conn->connect_errno . ") " . $this->dbConnector->conn->connect_error
            ]);
        }

        if(!$result = $this->dbConnector->query("SELECT email FROM users WHERE email='" . $all['email'] . "'")) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "Error while checking if user already exists in MySQL: (" . $this->dbConnector->conn->errno . ") " . $this->dbConnector->conn->error
            ]));
        }
        if($result->num_rows !== 0) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "User with this email already exists."
            ]));
        }

        if(!$result = $this->dbConnector->query("INSERT INTO users (email, password) VALUES ('" . $all['email'] . "', '" . $all['password'] . "')")) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "Error while adding user to MySQL: (" . $this->dbConnector->conn->errno . ") " . $this->dbConnector->conn->error
            ]));
        }

        $addedUserId = $this->dbConnector->conn->insert_id;

        print_r(json_encode([
            "status" => "ok",
            "user" => [
                "id" => $addedUserId,
                "email" => $all['email'],
                "password" => $all['password']
            ]
        ]));
    }

    public function getUser($request, $id)
    {
        if($this->dbConnector->conn->connect_errno) {
            return json_encode([
                "status" => "error",
                "errorMessage" => "Error while connecting to MySQL: (" . $this->dbConnector->conn->connect_errno . ") " . $this->dbConnector->conn->connect_error
            ]);
        }

        if(!$result = $this->dbConnector->query("SELECT * FROM users WHERE id='" . $id . "'")) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "Error while getting user from MySQL: (" . $this->dbConnector->conn->errno . ") " . $this->dbConnector->conn->error
            ]));
        }

        if($result->num_rows === 0) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "No user with this id."
            ]));
        } else {
            $user = $result->fetch_assoc();
            print_r(json_encode([
                "status" => "ok",
                "user" => $user
            ]));
        }
    }

    public function deleteUser($request, $id)
    {
        if($this->dbConnector->conn->connect_errno) {
            return json_encode([
                "status" => "error",
                "errorMessage" => "Error while connecting to MySQL: (" . $this->dbConnector->conn->connect_errno . ") " . $this->dbConnector->conn->connect_error
            ]);
        }

        if(!$result = $this->dbConnector->query("DELETE FROM users WHERE id='" . $id . "'")) {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "Error while deleting user from MySQL: (" . $this->dbConnector->conn->errno . ") " . $this->dbConnector->conn->error
            ]));
        }

        if($this->dbConnector->conn->affected_rows > 0) {
            return print_r(json_encode([
                "status" => "ok"
            ]));
        } else {
            return print_r(json_encode([
                "status" => "error",
                "errorMessage" => "Cannot delete user with id=" . $id . ". No user with this id"
            ]));
        }
    }
}