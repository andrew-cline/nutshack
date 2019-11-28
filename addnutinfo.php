<!doctype html>
<?php
session_start();
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("Add Nut Info");
require('nutshack_topbar.php');

if (we_are_not_admin()) {
    exit;
  }
  

# Code for your web page follows.
if (!isset($_POST['submit'])) {
  ?>
  <h2>Add Nut</h2>
  <form action="addnutinfo.php" method="post">
    <table border="0">
      <tr bgcolor="#cccccc">
        <td width="100">Field</td>
        <td width="300">Value</td>
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
        <td>Vitamins</td>
        <td align="left"><input type="text" name="vitamins" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td>Minerals</td>
        <td align="left"><input type="text" name="minerals" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td>Protein</td>
        <td align="left"><input type="text" name="protein" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td>Fat</td>
        <td align="left"><input type="text" name="fat" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td>Carbohydrates</td>
        <td align="left"><input type="text" name="carb" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td>Calories</td>
        <td align="left"><input type="text" name="calories" size="255" maxlength="255"></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
<?php
} else {
  $name = $_POST['name'];
  $vitamins = $_POST['vitamins'];
  $minerals = $_POST['minerals'];
  $protein = $_POST['protein'];
  $fat = $_POST['fat'];
  $carb = $_POST['carb'];
  $calories = $_POST['calories'];

  //clean up and validate data
  try {
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $db->query("SELECT nut_info_id FROM nut_general where name = '$name';")->fetch(PDO::FETCH_ASSOC);
    $previd = $sql['nut_info_id'];
    if( !isset($previd))
    {
        $db->exec("INSERT INTO nut_info (vitamins, minerals, protein, fat, carb, calories) VALUES ('$vitamins', '$minerals', '$protein', '$fat', '$carb', '$calories');");
        $last_id = $db->lastInsertId();
        $db->exec("Update nut_general set nut_info_id = $last_id where name = '$name';");
    }
    else
    {
        $db->exec("Update nut_info set vitamins = '$vitamins', minerals = '$minerals', protein = '$protein', fat = '$fat', carb = '$carb', calories = '$calories' where id = $previd;");
        $last_id = $previd;
    }
    
    
    


    //now output the data from the insert to a simple html table...
    print "<h2>Nut Info Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td>Name</td><td>Vitamins</td><td>Minerals</td><td>Protein</td><td>Fat</td><td>Carbohydrates</td><td>Calories</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM nut_info where id = '$last_id';")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>" . $name . "</td>";
    print "<td>" . $row['vitamins'] . "</td>";
    print "<td>" . $row['minerals'] . "</td>";
    print "<td>" . $row['protein'] . "</td>";
    print "<td>" . $row['fat'] . "</td>";
    print "<td>" . $row['carb'] . "</td>";
    print "<td>" . $row['calories'] . "</td>";
    print "</tr>";
    print "</table>";
    $id = $row['id'];
    

    // close the database connection
    $db = NULL;
  } catch (PDOException $e) {
    echo 'Exception: ' . $e->getMessage();
    echo "<br/>";
    $db = NULL;
  }
}
require('nutshack_footer.php');
?>