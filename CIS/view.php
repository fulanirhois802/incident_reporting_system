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
	
  $logoutGoTo = "admin.php";
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

$MM_restrictGoTo = "admin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php @session_start(); ?>
<?php require_once('Connections/db_connect.php'); ?>
<?php
$colname_views = "-1";
if (isset($_GET['userid'])) {
  $colname_views = (get_magic_quotes_gpc()) ? $_GET['userid'] : addslashes($_GET['userid']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_views = sprintf("SELECT * FROM incidents WHERE inc_id = %s", $colname_views);
$views = mysqli_query($db_connect, $query_views) or die(mysql_error());
$row_views = mysqli_fetch_assoc($views);
$totalRows_views = mysqli_num_rows($views);
?><!DOCTYPE html>
<head>
<title>Administrator: Views</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
<?php include 'Adm-head.php'; ?>

	
<!--START main body-->
<body>
<!--Menu header-->
<?php include 'Adm-menu-admin.php'; ?>
<!--Content body-->
<a class="glyphicon glyphicon-backward" href="adminpage.php">Back to Reports</a>
<div class="container">
<h4><p class="text-info">Welcome: <i class="text-danger">Administrator</i></p></h4>

<form name="form1" method="post" action="">
  <table width="80%" border="0">
    <tr>
      <td>Incident</td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input name="textfield" class="form-control" type="text" readonly value="<?php echo $row_views['inc_id']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td>Campus</td>
      <td>
	  <div class="form-group"> 
   	<div class="col-sm-4">
	<input name="textfield2" class="form-control" type="text" readonly value="<?php echo $row_views['place']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td>Incident type </td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input name="textfield3" class="form-control" type="text" readonly value="<?php echo $row_views['type']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td>Reporter's Name </td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input type="text" class="form-control" name="textfield4" readonly value="<?php echo $row_views['username']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td>Phone Number </td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input type="text" class="form-control" name="textfield5" readonly value="Null">
	</div>
	</div>
	</td>
    </tr>
  </table>
  
</form>

<table border="0" class="table">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td colspan="2" >Time</td>
        </tr>
      <tr>
        <td>Time Slot </td>
        <td><?php echo $row_views['time']; ?></td>
      </tr>
      <tr>
        <td>Date</td>
        <td><?php echo $row_views['Timestamp']; ?></td>
      </tr>
	  <tr>
        <td colspan="2">Incident</td>
        </tr>
      <tr>
        <td >Type</td>
        <td><?php echo $row_views['type']; ?></td>
      </tr>
    </table></td>
    </td>
  </tr>
</table>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysqli_free_result($views);
?>
