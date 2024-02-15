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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "report")) {
  $insertSQL = sprintf("INSERT INTO incidents (username, reg_no, `time`, type, place) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['reg_no'], "text"),
                       GetSQLValueString($_POST['time'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['place'], "text"));

  mysqli_select_db($db_connect, $database_db_connect);
  $Result1 = mysqli_query($db_connect, $insertSQL) or die(mysql_error());

  $insertGoTo = "report.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_session = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_session = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_session = sprintf("SELECT * FROM registration WHERE username = '%s'", $colname_session);
$session = mysqli_query($db_connect, $query_session) or die(mysql_error());
$row_session = mysqli_fetch_assoc($session);
$totalRows_session = mysqli_num_rows($session);

$maxRows_incident = 10;
$pageNum_incident = 0;
if (isset($_GET['pageNum_incident'])) {
  $pageNum_incident = $_GET['pageNum_incident'];
}
$startRow_incident = $pageNum_incident * $maxRows_incident;

$colname_incident = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_incident = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_incident = sprintf("SELECT * FROM incidents WHERE username = '%s'", $colname_incident);
$query_limit_incident = sprintf("%s LIMIT %d, %d", $query_incident, $startRow_incident, $maxRows_incident);
$incident = mysqli_query($db_connect, $query_limit_incident) or die(mysql_error());
$row_incident = mysqli_fetch_assoc($incident);

if (isset($_GET['totalRows_incident'])) {
  $totalRows_incident = $_GET['totalRows_incident'];
} else {
  $all_incident = mysqli_query($db_connect, $query_incident);
  $totalRows_incident = mysqli_num_rows($all_incident);
}
$totalPages_incident = ceil($totalRows_incident/$maxRows_incident)-1;
?><!DOCTYPE html>
<head>
<title>Incident System: Report</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
	<?php include 'Adm-head.php'; ?>

	
<!--START main body-->
<body>
<!--Menu header-->
  		<?php include 'Adm-menu.php'; ?>
<!--Content body-->
<body>
<div id="content">

Hello: <i style="color:#0000CC;"><?php echo $row_session['first']; ?>  <?php echo $row_session['other']; ?></i>
	<form name="report" method="POST" action="<?php echo $editFormAction; ?>">
	<table class="report_table">
		<tr><input type="hidden" name="username" value="<?php echo $row_session['username']; ?>"></tr>
			<tr><td>Members ID:</td><td>
			<div class="form-group"> 
   	<div class="col-sm-4">
	<input class="form-control" readonly type="text" name="reg_no" value="<?php echo $row_session['reg_no']; ?>" />
	</div>
	</div>
	</td></tr>
			<tr><td>Time slot:</td><td>
			<div class="form-group"> 
   	<div class="col-sm-4">
	<select class="form-control" name="time"><option>00hrs - 01hrs</option><option>01hrs - 02hrs</option><option>02hrs - 03hrs</option><option>03hrs - 04hrs</option><option>04hrs - 05hrs</option><option>05hrs - 06hrs</option><option>6hrs - 07hrs</option><option>07hrs - 08hrs</option><option>08hrs - 09hrs</option><option>09hrs - 10hrs</option><option>11hrs - 12hrs</option><option>12hrs - 13hrs</option><option>13hrs - 14hrs</option><option>14hrs - 15hrs</option><option>15hrs - 16hrs</option><option>16hrs - 17hrs</option><option>17hrs - 18hrs</option><option>18hrs - 19hrs</option><option>19hrs - 20hrs</option><option>20hrs - 21hrs</option><option>21hrs - 22hrs</option><option>22hrs -232hrs</option><option>23hrs - 0hrs</option></select>
	</div>
	</div>
	</td></tr>
			
		<tr><td>Incident type:</td><td>
		<div class="form-group"> 
   	<div class="col-sm-4">
	<input class="form-control" name="type" type="text" required></div><small class="text-info">Medical, Earthquake, e.t.c</small>
	</div>
	</td></tr>
		 <tr><td>Place of Incident:</td>
		<td>
		<div class="form-group"> 
   	<div class="col-sm-4">
	<select class="form-control" name="place"><option>Siriba</option><option>College</option></select>
	</div>
	</div></td></tr>
		</tr>
		</tr>
		<tr><td></td><td>
		<div class="form-group"> 
   	<div class="col-sm-4">
	<input type="submit" class="btn btn-primary" value="Submit">
	</div>
	</div>
	</td></tr>
	  </table>
			
    <input type="hidden" name="MM_insert" value="report">
  </form>


    <table border="0" class="table table-striped">
	<thread><tr><td>Reg No</td>
					<td>Type</td>
					<td>Time</td>
					<td>Place</td>
                    <td>View</td>
					</tr></thread>
      <?php do { ?>
		<tbody>
		<tr>
		  <td><?php echo $row_incident['reg_no']; ?></td>
		  <td><?php echo $row_incident['type']; ?></td>
          <td><?php echo $row_incident['time']; ?></td>
          <td><?php echo $row_incident['place']; ?></td>
          <td><a href="userview.php?UsgeccagsHwFSnsgAr=<?php echo $row_incident['inc_id']; ?>">View</a></td>
        </tr>
		</tbody>
		<tr></tr>
        <?php } while ($row_incident = mysqli_fetch_assoc($incident)); ?>
    </table>
</div>
</body>

</html><?php
mysqli_free_result($session);

mysqli_free_result($incident);
?>