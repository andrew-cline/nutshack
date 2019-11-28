<!doctype html>
<?php
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("List of Nuts");
require('nutshack_topbar.php');
?>
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
<?php

try
{
  //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    for($i = 0; $i <= count($_POST); $i++)
    {
        if( isset($_POST[$i]))
        {
            echo $_POST[$i];
        }
    }
?>

<?

  // close the database connection
  $db = NULL;
}
catch(PDOException $e)
{
  echo 'Exception : '.$e->getMessage();
  echo "<br/>";
  $db = NULL;
}
require('nutshack_footer.php');
?>
