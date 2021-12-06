<?php
    $resultBox = '';
    if(isset($_POST['sBtn'])) {
        include_once('./functions.php');
        $result = login(
            $_POST['email'],
            $_POST['password']
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

<form action="?page=login" method="POST">
    <label for="email">
        <span>Email: </span>
        <input type="email" name="email" required>
    </label>
    <label for="password">
        <span>Password:</span>
        <input type="password" name="password" required>
    </label>
    <input type="submit" value="Log in" name="sBtn">
    <?php if($resultBox != '') echo $resultBox ?>
</form>