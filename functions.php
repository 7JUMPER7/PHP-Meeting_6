<?php
    $usersFile = './users.json';

    class User {
        public $email;
        public $password;
        public $name;

        function __construct($email, $password, $name) {
            $this->email = $email;
            $this->password = md5($password);
            $this->name = $name;
        }

        function show() {
            return "<div>".$this->email." | ".$this->password." | ".$this->name."</div>";
        }
    }

    function checkFile() {
        global $usersFile;
        if(!file_exists($usersFile)) {
            file_put_contents($usersFile, '[]');
        } else {
            $json = file_get_contents($usersFile);
            $buf = json_decode($json);
            if(gettype($buf) != 'array') {
                file_put_contents($usersFile, '[]');
            }
        }
    }

    function isLoggedIn() {
        if(session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION['email'])) {
            return ['email' => $_SESSION['email'], 'name' => $_SESSION['username']];
        }
        return null;
    }

    function getUsers() {
        error_reporting(0);
        global $usersFile;
        checkFile();
        $json = file_get_contents($usersFile);
        $users = json_decode($json);
        return $users;
    }

    function register($name, $email, $password, $password2) {
        $emailPattern = "/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD";

        $newUser = new User($email, $password, $name);
        $users = getUsers();
        $result = array_filter($users, function($item) use($email) {
            return $item->email == $email;
        });

        error_reporting(0);
        global $usersFile;
        if($result) {
            return ['status' => false, 'message' => 'Email already exists'];
        }
        if(preg_match($email, $emailPattern)) {
            return ['status' => false, 'message' => 'Wrong email'];
        }
        if($password != $password2) {
            return ['status' => false, 'message' => 'Passwords doest\' match'];
        }

        $users[] = $newUser;
        $json = json_encode($users);
        if(file_put_contents($usersFile, $json)) {
            if(session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $name;
            return ['status' => true, 'message' => 'Successfully registered'];
        }
        return ['status' => false, 'message' => 'Some error happened'];
    }

    function login($email, $password) {
        $users = getUsers();
        $result = array_filter($users, function($item) use($email) {
            return $item->email == $email;
        });
        
        if($result) {
            $firstKey = array_key_first($result);
            if($result[$firstKey]->password == md5($password)) {
                if(session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['email'] = $result[$firstKey]->email;
                $_SESSION['username'] = $result[$firstKey]->name;
                return ['status' => true, 'message' => 'Logged in successfully'];
            }
            return ['status' => false, 'message' => 'Wrong password'];
        }
        return ['status' => false, 'message' => 'Email doesn\'t found'];
    }

    function getImages() {
        $directory = './images';
        $files = array_diff(scandir($directory), array('..', '.'));
        return $files;
    }
?>