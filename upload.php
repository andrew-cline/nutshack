<!doctype html>
<?php
session_start();
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("Upload Image");
require('nutshack_topbar.php');
if (we_are_not_admin()) {
    exit;
  }
  


?>
<h2>Upload Media</h2>
<form enctype="multipart/form-data" action="nutshack_process_image.php" method=post>
<table>
        <tr>
        <td>Name</td>
        <td align="left">
          <select name="name">
            <?php
              // Replace text field with a select pull down menu.
              try {
                //open the database
                $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //display all types in the types table
                $result = $db->query('SELECT * FROM nut_general');
                foreach ($result as $row) {
                  print "<option value=" . $row['name'] . ">" . $row['name'] . "</option>";
                }

                // close the database connection
                $db = NULL;
              } catch (PDOException $e) {
                echo 'Exception : ' . $e->getMessage();
                echo "<br/>";
                $db = NULL;
              }
              ?>
          </select>
        </td>
      </tr>
    <tr>
  <input type="hidden"  name="MAX_FILE_SIZE" value="10000000000">
  <td>Upload this file:<input name="userfile" type="file"></td>
  <td><input type="submit" value="Upload file"></td>
            </tr>
</form>
