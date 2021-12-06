<?php
    $links = ['gallery' => './pages/gallery.php', 'upload' => './pages/upload.php'];
    $loginLinks = ['register' => './pages/registration.php', 'login' => './pages/login.php'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/header.css" rel="stylesheet">
    <link href="./css/form.css" rel="stylesheet">
    <link href="./css/gallery.css" rel="stylesheet">
</head>
<body>
    <header id="header">
        <?php
            include_once('./functions.php');
            include_once('./pages/menu.php');
            generateMenu($links, $loginLinks, isLoggedIn());
        ?>
    </header>

    <main>
        <?php
            if(!isset($_GET['page'])) {
                $_GET['page'] = 'home';
            } 

            if(array_key_exists($_GET['page'], $links)) {
                include_once($links[$_GET['page']]);
            } else if(array_key_exists($_GET['page'], $loginLinks)) {
                include_once($loginLinks[$_GET['page']]);
            } else {
                include_once($links['home']);
            }
        ?>
    </main>

    <footer>

    </footer>

    <script src="./js/header.js"></script>
</body>
</html>