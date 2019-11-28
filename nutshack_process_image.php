<!doctype html>
<?php
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("Upload Media");
require('nutshack_topbar.php');

echo "<h2>Processing Media from list..</h2>";
$name = $_POST['name'];
$name = strtolower($name);
if($_FILES['userfile']['error'])
{
    echo 'Problem';
    switch($_FILES['userfiles']['error'])
    {
        case 1: echo 'File exceeded upload_max_filesize'; break;
        case 2: echo 'File exceeded max_file_size'; break;
        case 3: echo 'File only partially uploaded'; break;
        case 4: echo 'No file uploaded'; break;
    }
    exit;
}



$upfile ='./images/'.$name.'.png';

if(is_uploaded_file($_FILES['userfile']['tmp_name']))
{
    if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $upfile))
    {
        echo 'Problem: Could not move file to destination directory';
        exit;
    }
}
else
{
    echo 'Problem: Possible file upload attack. Filename: ';
    echo $_FILES['userfile']['name'];
    exit;
}

echo 'File uploaded successfully<br><br>';


?>