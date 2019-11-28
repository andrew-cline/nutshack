<?php
function html_head($title) {
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="utf-8">';
    echo "<title>$title</title>";
    echo '<link rel="stylesheet" href="nut.css">';
    echo '</head>';
    echo '<body>';
}
function validate_nut($name, $type, $description) {
    $error_messages = array(); # Create empty error_messages array.
    if ( strlen($name)  == 0 ) {
      array_push($error_messages, "Name field must have a name.");
    }
  
    if ( strlen($type) == 0 ) {
      array_push($error_messages, "Type field must have a type.");
    }
  
    if ( strlen($description) == 0 ) {
      array_push($error_messages, "Description field must have a description.");
    }
  
    try {
      //open the database
      $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      if ( strlen($type) != 0 ) {
        $sql = "SELECT COUNT(*) FROM nut_types WHERE type = '$type'";
        $result = $db->query($sql)->fetch();
        if ( $result[0] == 0) {
          array_push($error_messages, "Type $type is not defined. Type must be valid.");
        }
      }
        
      if ( strlen($name) != 0 ) {
        $sql = "SELECT COUNT(*) FROM nut_general WHERE name = '$name'";
        $result = $db->query($sql)->fetch();
        if ( $result[0] > 0) {
          array_push($error_messages, "$name is not unique. names must be unique.");
        }
      }
    }
  
    catch(PDOException $e){
      echo 'Exception : '.$e->getMessage();
      echo "<br/>";
      $db = NULL;
    }
  
    return $error_messages;
  }
function we_are_not_admin()
{
  if (empty($_SESSION['valid_user'])) {
    echo "Only administrators can execute this function.<br/>";
  	return true;
	}
}
function try_again($str) {
    echo $str;
    echo "<br/>";
    //the following emulates pressing the back button on a browser
    echo '<a href="#" onclick="history.back(); return false;">Try Again</a>';
    exit;
  }