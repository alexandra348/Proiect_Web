<?php

require_once __DIR__ . '/../services/UserService.php';

class UserController {

    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function register($data, $role)
    {
        try {

            $this->service->create($data, $role);

            http_response_code(201);

            return [
                "success" => true,
                "message" => "User created successfully"
            ];

        } catch(Exception $e) {

            http_response_code(400);

            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }
    }



    public function getAll()
    {
        try {

            $users = $this->service->getAll();
            http_response_code(200);

            return [
                "success"=>true,
                "data"=>$users
            ];

        }
        catch(Exception $e){

            http_response_code(500);

            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }
    }



    public function getById($id)
    {

        try {

            $user = $this->service->findById($id);
            http_response_code(200);

            return [
                "success"=>true,
                "data"=>$user
            ];

        }
        catch(Exception $e){

            http_response_code(404);

            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }

    }



    public function update($id, $data, $user)
    {

        try{

            $this->service->update($id, $data, $user);

            http_response_code(200);

            return [
                "success"=>true,
                "message"=>"User updated",
                "passwordChanged"=>!empty($data['password'])
            ];

        }
        catch(Exception $e){

            http_response_code(400);

            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }

    }



    public function delete($id)
    {

        try{

            $this->service->delete($id);
            http_response_code(200);
            return [
                "success"=>true,
                "message"=>"User deleted"
            ];

        }
        catch(Exception $e){

            http_response_code(404);
            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }

    }

}