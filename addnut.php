<!doctype html>
<?php
session_start();
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("Add Nut");
require('nutshack_topbar.php');
if (we_are_not_admin()) {
    exit;
  }
  

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
<h2>Add Nut</h2>
<form action="addnut.php" method="post">
  <table border="0">
    <tr bgcolor="#cccccc">
      <td width="100">Field</td>
      <td width="300">Value</td>
    </tr>
    <tr>
      <td>Name</td>
      <td align="left"><input type="text" name="name" size="35" maxlength="35"></td>
    </tr>
    <tr>
      <td>Type</td>
      <td align="left">
		 <select name="type">
<?php
  // Replace text field with a select pull down menu.
  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display all types in the types table
    $result = $db->query('SELECT * FROM nut_types');
    foreach($result as $row)
    {
      print "<option value=".$row['type'].">".$row['type']."</option>";
    }

    // close the database connection
    $db = NULL;
  }

  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
  }
?>
		</select>
      </td>
    </tr>
    <tr>
      <td>Description</td>
      <td align="left"><textarea name="description" rows="4" cols="100"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<?php
} else {
  $name = $_POST['name'];
  $type = $_POST['type'];
  $description = $_POST['description'];
  

  //clean up and validate data
  $errors = validate_nut($name, $type, $description);
    if(empty($errors))
    {
        echo "Name: $name<br/>";
        echo "Type : $type<br/>";
        echo "Description: $description<br/>";
        echo "Image Location: images/$image<br/>";

        try {
            $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("INSERT INTO nut_general (name, type, description, image) VALUES ('$name','$type','$description', 'images/$image' );");
            $last_id = $db->lastInsertId();
   
            //now output the data from the insert to a simple html table...
            print "<h2>Nut Added</h2>";
            print "<table border=1>";
            print "<tr>";
            print "<td>Id</td><td>Name</td><td>Description</td><td>Type</td>";
            print "</tr>";
            $row = $db->query("SELECT * FROM nut_general where id = '$last_id'")->fetch(PDO::FETCH_ASSOC);
            print "<tr>";
            print "<td>".$row['id']."</td>";
            print "<td>".$row['name']."</td>";
            print "<td>".$row['description']."</td>";
            print "<td>".$row['type']."</td>";
            print "</tr>";
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
    else
    {
        echo "Errors found in media entry:<br/>";
        echo "Name: $name<br/>";
        echo "Type: $type<br/>";
        echo "Description: $description<br/>";
        foreach($errors as $error)
        {
            echo $error."<br/>";
        }

    }
}
?>
