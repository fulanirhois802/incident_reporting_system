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
?><?php require_once('Connections/db_connect.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update_form")) {
  $updateSQL = sprintf("UPDATE incidents SET reg_no=%s, `time`=%s, type=%s, place=%s WHERE inc_id=%s",
                       GetSQLValueString($_POST['reg_no'], "text"),
                       GetSQLValueString($_POST['time'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['place'], "text"),
                       GetSQLValueString($_POST['inc_id'], "int"));

  mysqli_select_db($db_connect, $database_db_connect);
  $Result1 = mysqli_query($db_connect, $updateSQL) or die(mysql_error());

  $updateGoTo = "updatesuccess.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_views = "-1";
if (isset($_GET['UsgeccagsHwFSnsgAr'])) {
  $colname_views = (get_magic_quotes_gpc()) ? $_GET['UsgeccagsHwFSnsgAr'] : addslashes($_GET['UsgeccagsHwFSnsgAr']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_views = sprintf("SELECT * FROM incidents WHERE inc_id = %s", $colname_views);
$views = mysqli_query($db_connect, $query_views) or die(mysql_error());
$row_views = mysqli_fetch_assoc($views);
$totalRows_views = mysqli_num_rows($views);
?><!DOCTYPE html>
<head>
<title>User View Incident</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
<?php include 'Adm-head.php'; ?>

	
<!--START main body-->
<body>
<!--Menu header-->
<?php include 'Adm-menu.php'; ?>
<!--Content body-->
<div class="container">
Welcome: <span class="text-info"><?php echo $row_views['reg_no']; ?></span>

<form name="update_form" method="POST" action="<?php echo $editFormAction; ?>">
<input type="text"  name="inc_id" value="<?php echo $row_views['inc_id']; ?>" hidden>
  <table width="80%" border="0">
    <tr>
      <td><strong>Registration Number</strong></td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input name="reg_no" class="form-control" readonly="" type="text" value="<?php echo $row_views['reg_no']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td><strong>Incident type</strong> </td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input name="type" class="form-control" type="text" value="<?php echo $row_views['type']; ?>">
	</div>
	</div></td>
    </tr>
    <tr>
      <td><strong>Time Slot</strong></td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input type="text" class="form-control" value="<?php echo $row_views['time']; ?>" name="time">
	</div>
	</div></td>
    </tr>
    <tr>
      <td><strong>Place</strong></td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input type="text" class="form-control" value="<?php echo $row_views['place']; ?>" name="place">
	</div>
	</div>
	<br>
	<br>
	<tr>
      <td><strong></strong></td>
      <td><div class="form-group"> 
   	<div class="col-sm-4">
	<input type="submit" class="btn btn-primary" value="Update">
	</div>
	</div>
	
	</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="update_form">
</form>
</td>
    </td>
  </tr>
</table>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysqli_free_result($views);
?>