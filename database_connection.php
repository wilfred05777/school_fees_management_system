<?php

//database_connection.php

// $base_url = 'http://localhost/tutorial/school_fees_management_system/';
$base_url = 'http://localhost/school_fees_management_system/';

$connect = new PDO("mysql:host=localhost;dbname=sfms", "root", "");

$month_array = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

session_start();

function is_login()
{
  if (isset($_SESSION['admin_id'])) {
    return true;
  }
  return false;
}

function is_master_user()
{
  if (isset($_SESSION['user_type'])) {
    if ($_SESSION["user_type"] == 'Master') {
      return true;
    }
    return false;
  }
  return false;
}
