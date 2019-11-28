<!doctype html>
<?php
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("Search Nut");
require('nutshack_topbar.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
<h2>Search</h2>
<form action="nutsearch.php" method="post">
  <table>
    <tr>
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
      <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<?php
} else {
        try {
            $name = $_POST['name'];
            $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $result = $db->query("SELECT * from nut_general where name = '$name';");
            print "<table align='left'>";
            foreach($result as $row) {
                print "<tr>";
                print "<td>".$row['name']."</td>";
                print "<td>".$row['type']."</td>";
                print "<td>".$row['description']."</td>";
                print "<td><img src=".$row['image']." height='150' width='150'></td>";
                print "</tr>";
                $id = $row['nut_info_id'];
              }
            print "</table>";
            $result = $db->query("SELECT * from nut_info where id = $id;");
            print "<table>";
            foreach( $result as $row)
            {
                print "<tr><td>Vitamins:</td><td>".$row['vitamins']."</td></tr>";
                print "<tr><td>Minerals:</td><td>".$row['minerals']."</td></tr>";
                print "<tr><td>Protein per Serving:</td><td>".$row['protein']."</td></tr>";
                print "<tr><td>Fat per Serving:</td><td>".$row['fat']."</td></tr>";
                print "<tr><td>Carbohydrates per Serving:</td><td>".$row['carb']."</td></tr>";
                print "<tr><td>Calories per Serving:</td><td>".$row['calories']."</td></tr>";
                
            }
            print "</table>";
          // close the database connection
            $db = NULL;
        }
        catch(PDOException $e)
        {
            echo 'Exception: '.$e->getMessage();
            echo "<br/>";
            $db = NULL;
        }
    }
?>
