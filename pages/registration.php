<?php
    $resultBox = '';
    if(isset($_POST['sBtn'])) {
        include_once('./functions.php');
        $result = register(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['password2']
        );
        if($result['status']) {
            $resultBox = "<div class='resultBox success'>".$result['message']."</div>";
            ?>
            <script>
                setTimeout(() => {
                    window.location = '?page=home';
                }, 1000);
            </script>
            <?php
        } else {
            $resultBox = "<div class='resultBox fail'>".$result['message']."</div>";
        }
    }
?>

<form action="?page=register" method="POST">
    <label for="name">
        <span>Name: </span>
        <input type="text" name="name" required>
    </label>
    <label for="email">
        <span>Email: </span>
        <input type="email" name="email" required>
    </label>
    <label for="password">
        <span>Password:</span>
        <input type="password" name="password" required>
    </label>
    <label for="password2">
        <span>Confirm password:</span>
        <input type="password" name="password2" required>
    </label>
    <input type="submit" value="Register" name="sBtn">
    <?php if($resultBox != '') echo $resultBox ?>
</form>