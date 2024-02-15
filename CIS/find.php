<?php require_once('Connections/db_connect.php'); ?><?php
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

$MM_restrictGoTo = "Admin.php";
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
$colname_search = "-1";
if (isset($_POST['find'])) {
  $colname_search = (get_magic_quotes_gpc()) ? $_POST['find'] : addslashes($_POST['find']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_search = sprintf("SELECT * FROM incidents WHERE `time` = '%s' ORDER BY `time` DESC", $colname_search);
$search = mysqli_query($db_connect, $query_search) or die(mysql_error());
$row_search = mysqli_fetch_assoc($search);
$totalRows_search = mysqli_num_rows($search);

$colname_searcher = "-1";
if (isset($_POST['find'])) {
  $colname_searcher = (get_magic_quotes_gpc()) ? $_POST['find'] : addslashes($_POST['find']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_searcher = sprintf("SELECT * FROM incidents WHERE `Timestamp` = '%s' ORDER BY `Timestamp` DESC", $colname_searcher);
$searcher = mysqli_query($db_connect, $query_searcher) or die(mysql_error());
$row_searcher = mysqli_fetch_assoc($searcher);
$totalRows_searcher = mysqli_num_rows($searcher);

$maxRows_searcher = 10;
$pageNum_searcher = 0;
if (isset($_GET['pageNum_searcher'])) {
  $pageNum_searcher = $_GET['pageNum_searcher'];
}
$startRow_searcher = $pageNum_searcher * $maxRows_searcher;

$colname_searcher = "-1";
if (isset($_POST['find'])) {
  $colname_searcher = (get_magic_quotes_gpc()) ? $_POST['find'] : addslashes($_POST['find']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_searcher = sprintf("SELECT * FROM incidents WHERE type LIKE '%%%s%%' ORDER BY inc_id DESC", $colname_searcher);
$query_limit_searcher = sprintf("%s LIMIT %d, %d", $query_searcher, $startRow_searcher, $maxRows_searcher);
$searcher = mysqli_query($db_connect, $query_limit_searcher) or die(mysql_error());
$row_searcher = mysqli_fetch_assoc($searcher);

if (isset($_GET['totalRows_searcher'])) {
  $totalRows_searcher = $_GET['totalRows_searcher'];
} else {
  $all_searcher = mysqli_query($db_connect, $query_searcher);
  $totalRows_searcher = mysqli_num_rows($all_searcher);
}
$totalPages_searcher = ceil($totalRows_searcher/$maxRows_searcher)-1;
?>
<?php require("Libraries/Variables.php") ?>

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

<!--Content body-->

	<form name="search" method="post" action="">
		<div class="form-group"> 
   	<div class="col-sm-4">
	<input name="find" class="form-control" type="text">
	</div></div>
	<div class="form-group"> 
   	<div class="col-sm-4">
	 <input class="btn btn-default" type="submit" value="Search">
	</div></div>
</form>


	

    <table border="0" class="table">
      <tr>
	    <td><i>Type</i></td>
        <td><i>Time</i></td>
        <td><i>Place</i></td>
      </tr>
      <?php do { ?>
        <tr style="background-color:#FFCC66;">
		  <td><?php echo $row_searcher['type']; ?> <?php echo $row_search['type']; ?></td>
          <td><?php echo $row_searcher['time']; ?></td>
          <td><?php echo $row_searcher['place']; ?></td> 
		  <td><a href="view.php?userid=<?php echo $row_searcher['inc_id']; ?>">View</a></td>
        </tr>
		<tr bgcolor="#FFFFFF"></tr>
        <?php } while ($row_searcher = mysqli_fetch_assoc($searcher)); ?>
    </table>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysqli_free_result($search);

mysqli_free_result($searcher);
?>

