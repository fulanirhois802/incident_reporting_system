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
<?php @session_start(); ?>
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

$colname_profile = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_profile = $_SESSION['MM_Username'];
}
mysqli_select_db($db_connect, $database_db_connect);
$query_profile = sprintf("SELECT * FROM registration WHERE username = %s", GetSQLValueString($colname_profile, "text"));
$profile = mysqli_query($db_connect, $query_profile) or die(mysql_error());
$row_profile = mysqli_fetch_assoc($profile);
$totalRows_profile = mysqli_num_rows($profile);

$maxRows_DataTable = 10;
$pageNum_DataTable = 0;
if (isset($_GET['pageNum_DataTable'])) {
  $pageNum_DataTable = $_GET['pageNum_DataTable'];
}
$startRow_DataTable = $pageNum_DataTable * $maxRows_DataTable;

$colname_DataTable = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_DataTable = $_SESSION['MM_Username'];
}
mysqli_select_db($db_connect, $database_db_connect);
$query_DataTable = sprintf("SELECT * FROM messages WHERE m_name = %s", GetSQLValueString($colname_DataTable, "text"));
$query_limit_DataTable = sprintf("%s LIMIT %d, %d", $query_DataTable, $startRow_DataTable, $maxRows_DataTable);
$DataTable = mysqli_query($db_connect, $query_limit_DataTable) or die(mysql_error());
$row_DataTable = mysqli_fetch_assoc($DataTable);

if (isset($_GET['totalRows_DataTable'])) {
  $totalRows_DataTable = $_GET['totalRows_DataTable'];
} else {
  $all_DataTable = mysqli_query($db_connect, $query_DataTable);
  $totalRows_DataTable = mysqli_num_rows($all_DataTable);
}
$totalPages_DataTable = ceil($totalRows_DataTable/$maxRows_DataTable)-1;

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "contact")) {
  $insertSQL = sprintf("INSERT INTO messages (`m_name` , `m_message` ,`m_reply` ) VALUES (%s, %s, %s )",
                       GetSQLValueString($_POST['m_name'], "text"),
					   GetSQLValueString($_POST['m_message'], "text"),
					   GetSQLValueString($_POST['m_reply'], "text")
					   );

  mysqli_select_db( $db_connect, $database_db_connect);
  $Result1 = mysqli_query( $db_connect, $insertSQL) or die(mysql_error());

  $insertGoTo = "contact.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysqli_select_db($db_connect, $database_db_connect);
$query_Register = "SELECT * FROM messages";
$Register = mysqli_query( $db_connect, $query_Register) or die(mysql_error());
$row_Register = mysqli_fetch_assoc($Register);
$totalRows_Register = mysqli_num_rows($Register);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Students</title>
	<link href="css/bootstrap-3.3.7.css" rel="stylesheet" type="text/css"/>
	<?php include 'Adm-head.php'; ?>
</head>

<body>

<?php include 'Adm-menu.php'?>
<span class="glyphicon glyphicon-backward"></span><a href="report.php">Back</a>
<p align="center">
<div class="col-lg-10">
		<h3>Messages</h3><a href="contact.php"><span class="glyphicon glyphicon-refresh"> Refresh</a>
		<hr>
			<ul id="myTab1" class="nav nav-tabs">
				<li class="active"> <a href="#home1" data-toggle="tab"> Messages <span class="badge"><?php echo $totalRows_DataTable ?></span></a> </li>
				<li><a href="#pane2" data-toggle="tab">Send Message</a>
				</li>
			</ul>
<div id="myTabContent1" class="tab-content">
				<div class="tab-pane fade in active" id="home1">
                <br />
                  <table class="table-bordered" border="0">
                    <tr>
                      <td>Sent Message</td>
                      <td>Reply</td>
                    </tr>
                    <?php do { ?>
                      <tr>
                        <td><font style="font-weight:900; color:#0066FF"><?php echo $row_DataTable['m_message']; ?></font> <sub><em><?php echo $row_DataTable['m_name']; ?><br /><small style="color:#900;"><?php echo $row_DataTable['timestamp']; ?></small></em></sub></td>
                        </tr>
                        <tr>
                        <td></td><td><font style="font-weight:900; color:#F00;"><?php echo $row_DataTable['m_reply']; ?></font><sub>System<br /><small style="color:#900;"><em><?php echo $row_DataTable['timestamp']; ?></em></small></sub></td>
                      </tr>
                      <?php } while ($row_DataTable = mysqli_fetch_assoc($DataTable)); ?>
                  </table>
                </div>
				<div class="tab-pane fade" id="pane2">
				<p>Send Message</p>
<form name="contact" action="#" method="POST">

      <div class="input-group">
            <span class="input-group-addon">
      </span>
            <input name="m_name" type="text" value="<?php echo $row_profile['username']; ?>" readonly="readonly" class="form-control" aria-label="...">
      </div>
      <br>
      
      <div class="input-group">
            <span class="input-group-addon">
      </span>
	    <textarea  name="m_message" type="text" required="required" placeholder="Message" class="form-control" aria-label="..."></textarea>
      </div>
            <input name="m_reply" type="text" hidden="hidden" value="Awaiting Reply....">
      <br>
      <button type="submit" value="Send" class="btn btn-default ">Send</button>
      <input type="hidden" name="MM_insert" value="contact">

    
 
</form>
	<?php
mysqli_free_result($Register);
?>
		
    
				</div>
</div>

	</p>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-1.11.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-3.3.7.js" type="text/javascript"></script>
</body>
</html>
<?php
mysqli_free_result($profile);

mysqli_free_result($DataTable);
?>
