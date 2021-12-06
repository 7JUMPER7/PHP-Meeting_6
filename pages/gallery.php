<div class="container">
    <?php
        include_once('./functions.php');
        $i = 0;
        foreach(getImages() as $filename) {
            if($i % 2 == 0) {
                echo "<div class='image right'>";
            } else {
                echo "<div class='image left'>";
            }
            echo "<img src='./images/".$filename."' alt='".$filename."'>";
            echo "</div>";
            $i++;
        }
    ?>
</div>