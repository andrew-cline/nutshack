<div class="topnav">
    <a class="active" href="listofnuts.php">Nutshack</a>
<?php
    if(!empty($_SESSION['valid_user'])){
?>
    <a href="addnut.php">Add Nut</a>
    <a href="addnutinfo.php">Add Info</a>
    <a href="upload.php">Upload Image</a>
    <a href="nutshack_users.php">Add User</a>
    <a href="nutshack_admin.php">Admin</a>
<?php
    }else{
?>
    <a href="nutshack_login.php">Login</a>
<?php  } ?>
    <div class="topnav-right">
    <?php
    if(!empty($_SESSION['valid_user'])){
    ?>
        <a href="nutshack_logout.php">Logout</a>
    <?php
    }
    ?>
        <a href="nutsearch.php">Search</a>
    </div>
</div>