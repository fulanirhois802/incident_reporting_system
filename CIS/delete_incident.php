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
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

if ((isset($_POST['btn_delete'])) && ($_POST['btn_delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM incident_list WHERE inc_id=%s",
                       GetSQLValueString($_POST['btn_delete'], "int"));

  mysql_select_db($database_db_connect, $db_connect);
  $Result1 = mysql_query($deleteSQL, $db_connect) or die(mysql_error());

  $deleteGoTo = "delete_incident.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_del = 10;
$pageNum_del = 0;
if (isset($_GET['pageNum_del'])) {
  $pageNum_del = $_GET['pageNum_del'];
}
$startRow_del = $pageNum_del * $maxRows_del;

mysqli_select_db($db_connect, $database_db_connect);
$query_del = "SELECT * FROM incident_list ORDER BY inc_id ASC";
$query_limit_del = sprintf("%s LIMIT %d, %d", $query_del, $startRow_del, $maxRows_del);
$del = mysqli_query($db_connect, $query_limit_del) or die(mysql_error());
$row_del = mysqli_fetch_assoc($del);

if (isset($_GET['totalRows_del'])) {
  $totalRows_del = $_GET['totalRows_del'];
} else {
  $all_del = mysqli_query($db_connect, $query_del);
  $totalRows_del = mysqli_num_rows($all_del);
}
$totalPages_del = ceil($totalRows_del/$maxRows_del)-1;
?>
<!DOCTYPE html>
<head>
<title><?php echo $site_title ?>: About</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css">
<?php include 'Adm-head.php'; ?>
</head>
	
<!--START main body-->
<body>
<!--Menu header-->
<?php include 'Adm-menu-admin.php'; ?>
<form name="btn_delete">
<div class="container">
  <table border="0" class="table table-bordered" >
    <tr>
      <th>Incident ID</th>
      <th>Incident Name</th>
      <th><a  href=""><span class="glyphicon glyphicon-minus"></span>Delete</a></th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_del['inc_id']; ?></td>
        <td><?php echo $row_del['inc_name']; ?></td>
        <td><a href="del_inc.php?SQGatdJueisPlejsherhg=<?php echo $row_del['inc_id']; ?>"  type="submit" value="Delete" name="btn_delete" class="btn btn-danger">Delete</a></td>
      </tr>
      <?php } while ($row_del = mysqli_fetch_assoc($del)); ?>
  </table>
</div>
</form>
</body>

</html><?php
mysqli_free_result($del);
?>
