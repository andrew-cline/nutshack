<!doctype html>
<?php
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("nutshack users");
session_start();
require('nutshack_topbar.php');

if (we_are_not_admin()) {
  exit;
}

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
  <h2>Add User</h2>
  <form action="nutshack_users.php" method="post">
    <table border="0">
      <tr bgcolor="#cccccc">
        <td width="100">Field</td>
        <td width="300">Value</td>
      </tr>
      <tr>
        <td>First</td>
        <td align="left"><input type="text" name="first" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td>Last</td>
        <td align="left"><input type="text" name="last" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td>Email</td>
        <td align="left"><input type="text" name="email" size="70" maxlength="70"></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
      </tr>
    </table>
  </form>

<?php
} else {

/*validate the input and add to the database
 */ 

  $first = $_POST['first'];
  $last = $_POST['last'];
  $email = $_POST['email'];

  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //clean up and validate data
    $first = trim($first);
    if ( empty($first) ) {
      try_again("First name cannot be empty."); 
    }
    if( !ctype_alpha($first))
        {
            try_again("Only letters are accepted for first name");
        }
    $last = trim($last);
    if ( empty($last) ) {
      try_again("Last name cannot be empty.");
      
    }
    if( !ctype_alpha($last))
        {
            try_again("Only letters are accepted for last name");
        }
    $email = trim($email);
    if ( empty($email) ) {
      try_again("Email cannot be empty.");
      
    }
    
    //check for duplicate user name
    $sql = "select count(*) from nutshack_users where first = '$first' and last = '$last'";
    $result = $db->query($sql)->fetch(); //count the number of entries with the name
    if ( $result[0] > 0) {
      try_again($first." ".$last." is not unique. Names must be unique.");
    }
    
    //check for duplicate email
    $sql = "select count(*) from nutshack_users where email = '$email'";
    $result = $db->query($sql)->fetch(); //count the number of entries with the email
    if ( $result[0] > 0) {
      try_again("$email is not unique. Email must be unique.");
    }
    
    //insert data...
    $db->exec("INSERT INTO nutshack_users (first, last, email) VALUES ('$first', '$last', '$email');");
    
    //get the last id value inserted into the table
    $last_id = $db->lastInsertId();
     
    //now output the data from the insert to a simple html table...
    print "<h2>User Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td>Id</td><td>First</td><td>Last</td><td>Email</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM nutshack_users where id = '$last_id'")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['id']."</td>";
    print "<td>".$row['first']."</td>";
    print "<td>".$row['last']."</td>";
    print "<td>".$row['email']."</td>";
    print "</tr>";
    print "</table>";
  
    // close the database connection
    $db = NULL;
  }
  
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }
}
require('nutshack_footer.php')
?>