<?php
    function generateMenu($links, $loginLinks, $user) {
        foreach($links as $name => $link) {
            echo "<a href='?page=$name'>".ucfirst($name)."</a>";
        }

        echo "<div class='login'>";
        if($user) {
            echo '<h3>'.$user['name'].'</h3>';
        } else {
            foreach($loginLinks as $name => $link) {
                echo "<a href='?page=$name'>".ucfirst($name)."</a>";
            }
        }
        echo "</div>";
    }
?>