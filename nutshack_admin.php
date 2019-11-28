<!doctype html>
<?php
require('nutshack_functions.php');
require('nutshack_values.php');
html_head("nutshack administrator");
session_start();
require('nutshack_topbar.php');

if (we_are_not_admin()) {
  exit;
}

if (!isset($_POST['submit']))
{
  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

    <h2>Change Administration Priviledges</h2>
    <form action="nutshack_admin.php" method="post">
      <!-- display types -->
      <table border=1>
        <tr>
          <td>Click one to Change</td><td>User</td><td>Login</td>
        </tr>
        
<?php
    $result = $db->query("SELECT * FROM nutshack_users");
    foreach($result as $row)
    {
      print "<tr>";
      print "<td><input type='radio' name='id' value=".$row['id']."></td>";
      print "<td>".$row['first']." ".$row['last']."</td>";
      print "<td>".$row['login']."</td>";
      print "</tr>";
    }
?>
      </table>
      <p>Clicking an entry with a login will remove administration privileges.</p>
      <p>Clicking an entry without a login will enable administration privileges. Enter login and password below:</p>
      Login: <input type="text" name="login"/><br/>
      Password: <input type="text" name="password"/><br/>
      <input type="submit" name="submit" value = "Submit"/><br/>
    </form>

<?php
    
    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }
} else {
?>

  <h2>Administration Priviledges Changed</h2>

<?php
  $id = $_POST['id'];
  $login = $_POST['login'];
  $password = $_POST['password'];

  try
  {
    if (empty($id)) {
      echo "You did not select any users to change privileges.<br/>";
    } else {
      //open the database
      $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
      //update user as appropriate
      $sql = "SELECT * FROM nutshack_users where id = $id";
      $result = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
      if (empty($result['login'])) {
        //set the login and password to enable administrator privileges
        //clean up and validate data
        $login = trim($login);
        if ( empty($login) ) {
          try_again("Login cannot be empty.");
        }
        $password = trim($password);
        if ( strlen($password) < 8 ) {
          try_again("Password must be at least 8 characters.");
        }
        //change password into a sha1 hash
        $passwd = sha1($password);
        //check for duplicate login
        $sql = "select count(*) from nutshack_users where login = '$login'";
        $result = $db->query($sql)->fetch(); //count the number of entries with the name
        if ( $result[0] > 0) {
          try_again($login." is not unique. Logins must be unique.");
        }
        $db->exec("UPDATE nutshack_users SET login = '$login', password = '$passwd' WHERE id = $id");
      } else {
        //remove the login and password
        $db->exec("UPDATE nutshack_users SET login = NULL, password = NULL WHERE id = $id");
      }

      //now output the data to a simple html table...
      print "<table border=1>";
      print "<tr>";
      print "<td>User</td><td>Login</td>";
      print "</tr>";
      $sql = "SELECT * FROM nutshack_users where id = $id";
      $result = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
      print "<tr>";
      print "<td>".$result['first']." ".$result['last']."</td><td>".$result['login']."</td>";
      print "</tr>";
      print "</table>";
    }

    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }
}
?>