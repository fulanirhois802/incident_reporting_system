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
<?php require_once('Connections/db_connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "AddIncident")) {
  $insertSQL = sprintf("INSERT INTO incident_list (inc_name) VALUES (%s)",
                       GetSQLValueString($_POST['inc_name'], "text"));

  mysqli_select_db($db_connect, $database_db_connect);
  $Result1 = mysqli_query($db_connect, $insertSQL) or die(mysql_error());

  $insertGoTo = "List.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_lister = 10;
$pageNum_lister = 0;
if (isset($_GET['pageNum_lister'])) {
  $pageNum_lister = $_GET['pageNum_lister'];
}
$startRow_lister = $pageNum_lister * $maxRows_lister;

mysqli_select_db($db_connect, $database_db_connect);
$query_lister = "SELECT * FROM incident_list";
$query_limit_lister = sprintf("%s LIMIT %d, %d", $query_lister, $startRow_lister, $maxRows_lister);
$lister = mysqli_query($db_connect, $query_limit_lister) or die(mysql_error());
$row_lister = mysqli_fetch_assoc($lister);

if (isset($_GET['totalRows_lister'])) {
  $totalRows_lister = $_GET['totalRows_lister'];
} else {
  $all_lister = mysqli_query($db_connect, $query_lister);
  $totalRows_lister = mysqli_num_rows($all_lister);
}
$totalPages_lister = ceil($totalRows_lister/$maxRows_lister)-1;
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
<div class="container">
<!--Content body-->

	<form name="AddIncident" method="POST" action="<?php echo $editFormAction; ?>">
		<div class="form-group"> 
   	<div class="col-sm-4">
	<input name="inc_name" class="form-control" type="text">
	</div></div>
	<div class="form-group"> 
   	<div class="col-sm-3">
	 <input class="btn btn-default" type="submit" value="Add Incident">
	</div></div>
	<input type="hidden" name="MM_insert" value="AddIncident">
</form>
<br>
<br>
<br>
<p>
<table border="0" class="table table-bordered" >
      <tr>
        <th>Incident ID</th>
        <th>Incident ID</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_lister['inc_id']; ?></td>
          <td><?php echo $row_lister['inc_name']; ?></td>
        </tr>
        <?php } while ($row_lister = mysqli_fetch_assoc($lister)); ?>
    </table>
</p>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>
<?php
mysqli_free_result($lister);
?>