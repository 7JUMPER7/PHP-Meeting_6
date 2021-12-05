<?php
    include_once('./functions.php');
    $user = isLoggedIn();
    if($user) {
        var_dump($user);
    } else {
        var_dump('no');
    }
?>