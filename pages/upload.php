<?php
    $resultBox = '';
    if(isset($_POST['sBtn'])) {
        if($_FILES && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $filename = $_FILES['photo']['name'];
            $tmpName = $_FILES['photo']['tmp_name'];
            move_uploaded_file($tmpName, './images/'.$filename);
            $resultBox = "<div class='resultBox success'>Successfully uploaded</div>";
        } else {
            $resultBox = "<div class='resultBox fail'>Some error happened</div>";
        }
    }
?>

<?php
    if(isset($_SESSION['email'])) {
        ?>
            <form action="?page=upload" enctype="multipart/form-data" method="POST">
                <label for="photo">
                    <span>Select file:</span>
                    <label for="photoUploader" class="uploader">Browse</label>
                    <input type="file" style="color: black;" name="photo" id="photoUploader" accept="image/*" required>
                </label>
                <input type="submit" value="Upload" name="sBtn">
                <?php if($resultBox != '') echo $resultBox ?>
            </form>
        <?php
    } else {
        ?>
            <div style="margin-top: 30px;">
                Need to Register / Log in
            </div>
        <?php
    }
?>
