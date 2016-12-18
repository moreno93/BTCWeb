<?php

class User
{
    private $conn;
    private $_id;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_password;
    private $_percentage;
    private $_user;


    public function __construct($id, $email, $password){
        require_once dirname(__FILE__) . '/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();

        $this->_id = $id;
        $this->_email = $email;
        $this->_password = $password;
    }

    public function register($firstName, $lastName, $email, $password, $repeatPassword){
        if($password != $repeatPassword){
            throw new Exception("Passwords doesn't match!");
        }
        if ($this->isEmailExists($email)){
            throw new Exception("This email is already in use");
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO users(first_name, last_name, email, password) values (?, ?, ?, ?)');
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $passwordHash);
        $result = $stmt->execute();
        $stmt->close();
        if(!$result){
            throw new Exception("Error inserting into database");
        }
        return true;
    }

    public function login(){
        $user = $this->checkCredentials();
        if($user){
            $this->_user = $user;
            $_SESSION['user_id'] = $user['id'];
            return $user['id'];
        }
        throw new Exception("Username or password doesn't match");
    }

    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        }
        return false;
    }

    public function logout(){
        unset($_SESSION['user_id']);
        session_destroy();
        return true;
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id=?');
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $stmt->bind_result($id, $firstName, $lastName, $email, $password, $percentage, $created_at);
            $stmt->fetch();
            $tmp = array();
            $tmp["id"] = $id;
            $tmp['firstName'] = $firstName;
            $tmp['lastName'] = $lastName;
            $tmp["email"] = $email;
            $tmp["password"] = $password;
            $tmp['percentage'] = $percentage;
            $tmp['created_at'] = $created_at;
            $stmt->close();
            $this->_user = $tmp;
            return $this->_user;
        } else {
            throw new Exception("Cannot fetch user");
        }
    }

    public function redirect($url, $statusCode = 303){
        header('Location:' . $url, true, $statusCode);
        exit();
    }

    public function redirectWithMessage($url, $message, $statusCode = 303){
        header('Location:' . $url . '?m=' . $message, true, $statusCode);
        exit();
    }


    private function isEmailExists($email){
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows;
    }


    private function checkCredentials(){
        $stmt = $this->conn->prepare('SELECT id, email, password FROM users WHERE email=?');
        $stmt->bind_param('s', $this->_email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;

        if ($num_rows > 0){

            $stmt->bind_result($id, $email, $password);
            $stmt->fetch();
            $user = array();
            $user["id"] = $id;
            $user["email"] = $email;
            $user["password"] = $password;
            $stmt->close();
            if(password_verify($this->_password, $user['password'])){
                return $user;
            }
        }
        return false;
    }



}
