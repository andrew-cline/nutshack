<!doctype html>
<?php
session_start();
require('nutshack_functions.php');
html_head("nutshack logout");

//save valid_user to see if we were logged in to begin with
$old_user = $_SESSION['valid_user'];
unset($_SESSION['valid_user']);
session_destroy();

require('nutshack_topbar.php');
print "<h2>Log out</h2>";

if  (empty($old_user)) {
  print "You were not logged in to begin with.<br/>";  
} else {
  print "Logged out<br/>";
}

?>
