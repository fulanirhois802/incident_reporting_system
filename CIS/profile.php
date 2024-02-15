<?php @session_start(); ?>
<?php require_once('Connections/db_connect.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "logout.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE registration SET `first`=%s, other=%s, fuculty=%s, reg_no=%s, phone=%s, Dob=%s, username=%s, password=%s WHERE reg_id=%s",
                       GetSQLValueString($_POST['first'], "text"),
                       GetSQLValueString($_POST['other'], "text"),
                       GetSQLValueString($_POST['fuculty'], "text"),
                       GetSQLValueString($_POST['reg_no'], "text"),
                       GetSQLValueString($_POST['phone'], "int"),
                       GetSQLValueString($_POST['Dob'], "date"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['reg_id'], "int"));

  mysql_select_db($database_db_connect, $db_connect);
  $Result1 = mysql_query($updateSQL, $db_connect) or die(mysql_error());

  $updateGoTo = "report.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_profile = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_profile = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_db_connect, $db_connect);
$query_profile = sprintf("SELECT * FROM registration WHERE username = '%s'", $colname_profile);
$profile = mysql_query($query_profile, $db_connect) or die(mysql_error());
$row_profile = mysql_fetch_assoc($profile);
$totalRows_profile = mysql_num_rows($profile);
?>
<?php require("Libraries/Variables.php") ?>

<!DOCTYPE html>
<head>
<title><?php echo $site_title ?>: About</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
	
<!--START main body-->
<body>
<!--Menu header-->
  	<table class="menu-table">
		<tr>
			<td><a href="#" class="active">HOME</a></td><td><a href="about.php">ABOUT</a></td><td><a href="contact.php">CONTACT</a></td><td><a href="<?php echo $logoutAction ?>">Log out</a></td>
		</tr></table>
<!--Content body-->
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table class="profile_table">
    <tr valign="baseline">
    </tr>
    <tr valign="baseline">
      <td>First Name:</td>
      <td><input type="text" class="Pinput" name="first" value="<?php echo $row_profile['first']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Other Name:</td>
      <td><input type="text" class="Pinput" name="other" value="<?php echo $row_profile['other']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Fuculty:</td>
      <td><input type="text" class="Pinput" name="fuculty" value="<?php echo $row_profile['fuculty']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Reg no/Staff ID:</td>
      <td><input type="text"  class="Pinput" name="reg_no" value="<?php echo $row_profile['reg_no']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Phone Number:</td>
      <td><input type="number" class="Pinput" name="phone" value="<?php echo $row_profile['phone']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Date of Birth:</td>
      <td><input type="date" class="Pinput" name="Dob" value="<?php echo $row_profile['Dob']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Username:</td>
      <td><input type="text" class="Pinput" name="username" value="<?php echo $row_profile['username']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >Password:</td>
      <td><input type="text" class="Pinput" name="password" value="<?php echo $row_profile['password']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td >&nbsp;</td>
      <td><input type="submit" class="Psubmit" value="Update Profile"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="reg_id" value="<?php echo $row_profile['reg_id']; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysql_free_result($profile);
?>

