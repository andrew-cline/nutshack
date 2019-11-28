<!doctype html>
<?php
session_start();
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("List of Nuts");
require('nutshack_topbar.php');

# Code for your web page follows.
try
{
  //open the database
  $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<h2>List of Nuts</h2>
<table border=1>
  <tr>
    <td>Name</td><td>Type</td><td>Description</td><td>Image</td>
  </tr>

  <?php
  $query = "SELECT * FROM nut_general";
  $result = $db->query($query);
  foreach($result as $row) {
    print "<tr>";
    print "<td>".$row['name']."</td>";
    print "<td>".$row['type']."</td>";
    print "<td>".$row['description']."</td>";
    print "<td><img src=".$row['image']." height='150' width='150'></td>";
    print "</tr>";
  }

  print "</table>";

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
