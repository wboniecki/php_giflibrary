<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 12.10.17
 * Time: 18:20
 */

namespace Service;


use Entities\UsersEntity;

class UsersService extends Service
{
    public function save(UsersEntity $user) {
        $sql ="INSERT INTO Users
                (name, email, pass, privilege) VALUES 
                (:name, :email, :pass, :privilege)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "pass" => $this->passHash($user->getPass()),
            "privilege" => $user->getPrivilege()
        ]);

        if(!$result) {
            throw new Exception("could not save record (Users)");
        }
    }

    private function passHash($pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    private function passVerify($pass, $passHashed) {
        return password_verify($pass, $passHashed);
    }

    public function login($name, $pass) {
        try {
            $sql = "SELECT * 
                    FROM Users u
                    WHERE u.name = :name";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["name" => $name]);
            if($result) {
                $user = new UsersEntity($stmt->fetch());
                if($this->passVerify($pass, $user->getPass())) {
                    $_SESSION['user_session'] = $user->getId();
                    $_SESSION['user_name'] = $user->getName();
                    $_SESSION['user_id'] = $user->getId();
                    return true;
                } else {
                    return false;
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function isLoggedIn() {
        if(isset($_SESSION['user_session'])) {
            return true;
        }
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user_session']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_id']);
        return true;
    }

    public function update(UsersEntity $user) {

    }

    public function delete(UsersEntity $user) {

    }

    public function findById($id) {

    }

    public function findByName($name) {
        $sql = "SELECT *
                from Users u 
                where u.name = :name";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["name" => $name]);

        if($result) {
            return new UsersEntity($stmt->fetch());
        }
    }

    public function findByEmail($email) {

    }

    public function  findAll() {

    }


}