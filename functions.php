<?php
    $usersFile = './users.txt';

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
        $fd = fopen($usersFile, 'r');
        $users = [];
        if($fd) {
            while(!feof($fd)) {
                $userStr = fgets($fd);
                $userStr = rtrim($userStr);
                $user = mb_split('[|]', $userStr);
                if(count($user) > 2) {
                    $users[] = ['name' => $user[0], 'email' => $user[1], 'password' => $user[2]];
                }
            }
            fclose($fd);
        }
        return $users;
    }

    function register($name, $email, $password, $password2) {
        $emailPattern = "/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD";

        $users = getUsers();
        $result = array_filter($users, function($item) use($email) {
            return $item['email'] == $email;
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

        $fd = fopen($usersFile, 'a');
        if($fd) {
            if(fputs($fd, "$name|$email|".md5($password)."\n")) {
                if(session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $name;
                return ['status' => true, 'message' => 'Successfully registered'];
            }
            fclose($fd);
        }
        return ['status' => false, 'message' => 'Some error happened'];
    }

    function login($email, $password) {
        $users = getUsers();
        $result = array_filter($users, function($item) use($email) {
            return $item['email'] == $email;
        });
        
        if($result) {
            if($result[0]['password'] === md5($password)) {
                if(session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['email'] = $result[0]['email'];
                $_SESSION['username'] = $result[0]['name'];
                return ['status' => true, 'message' => 'Logged in successfully'];
            }
            return ['status' => false, 'message' => 'Wrong password'];
        }
        return ['status' => false, 'message' => 'Email doesn\'t found'];
    }
?>