<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService {

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    
    public function create($data)
    {
        if(
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ){
            throw new Exception(
                "Name, email and password are required"
            );
        }


        if(
            !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ){
            throw new Exception(
                "Invalid email format"
            );
        }


        if(
            $this->repository
                 ->findByEmail(
                     $data['email']
                 )
        ){
            throw new Exception(
                "Email already exists"
            );
        }


        $data['password']=password_hash(
            $data['password'],
            PASSWORD_BCRYPT
        );


        return $this->repository
                    ->create($data);
    }


    
    public function getAll()
    {
        return $this->repository
                    ->getAll();
    }


    
    public function findById($id)
    {
        if(!is_numeric($id)){
            throw new Exception(
                "Invalid user id"
            );
        }

        $user=
        $this->repository
             ->findById($id);

        if(!$user){
            throw new Exception(
                "User not found"
            );
        }

        unset($user['password']);

        return $user;
    }


    
    public function update(
        $id,
        $data
    )
    {

        if(
            !$this->repository
                   ->exists($id)
        ){
            throw new Exception(
                "User not found"
            );
        }


        if(
            empty($data['name']) ||
            empty($data['email'])
        ){
            throw new Exception(
                "Name and email required"
            );
        }


        if(
            !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ){
            throw new Exception(
                "Invalid email"
            );
        }


        return $this->repository
                    ->update(
                        $id,
                        $data
                    );
    }


    
    public function changePassword(
        $id,
        $oldPassword,
        $newPassword
    )
    {

        $user=
        $this->repository
             ->findById($id);


        if(!$user){
            throw new Exception(
                "User not found"
            );
        }


        if(
            !password_verify(
                $oldPassword,
                $user['password']
            )
        ){
            throw new Exception(
                "Old password incorrect"
            );
        }


        $hashed=
        password_hash(
            $newPassword,
            PASSWORD_BCRYPT
        );


        return $this->repository
                    ->updatePassword(
                        $id,
                        $hashed
                    );
    }



    public function delete($id)
    {

        if(
            !$this->repository
                   ->exists($id)
        ){
            throw new Exception(
                "User not found"
            );
        }


        return $this->repository
                    ->delete($id);
    }



    public function login(
        $email,
        $password
    )
    {

        $user=
        $this->repository
             ->verifyCredentials(
                $email
             );


        if(!$user){
            throw new Exception(
                "Invalid credentials"
            );
        }


        if(
            !password_verify(
                $password,
                $user['password']
            )
        ){
            throw new Exception(
                "Invalid credentials"
            );
        }


        unset(
            $user['password']
        );

        return $user;
    }

}