<?php @session_start(); ?>
<?php require_once('Connections/db_connect.php'); ?>
<?php
$colname_views = "-1";
if (isset($_GET['userid'])) {
  $colname_views = (get_magic_quotes_gpc()) ? $_GET['userid'] : addslashes($_GET['userid']);
}
mysql_select_db($database_db_connect, $db_connect);
$query_views = sprintf("SELECT * FROM incidents WHERE inc_id = %s", $colname_views);
$views = mysql_query($query_views, $db_connect) or die(mysql_error());
$row_views = mysql_fetch_assoc($views);
$totalRows_views = mysql_num_rows($views);
?><!DOCTYPE html>
<head>
<title><?php echo $site_title ?>: Admin Page</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
	
<!--START main body-->
<body>
<!--Menu header-->
  	<table class="menu-table">
		<tr>
			<td><a href="index.php" class="active">Home</a>|</td><td><a href="adminpage.php">List</a>|</td><td><a href="about.php">Casualties</a>|</td><td><a href="#">Log out</a></td>
		</tr></table>
<!--Content body-->
Welcome: Administrator 

<form name="form1" method="post" action="">
  <table width="80%" border="0">
    <tr>
      <td>Incident</td>
      <td><input name="textfield" class="Vinput" type="text" value="<?php echo $row_views['inc_id']; ?>"></td>
      <td><a href="Add.php"><img src="images/add.png" alt="Add" width="15" height="15">Add Incident </a></td>
    </tr>
    <tr>
      <td>Campus</td>
      <td><input name="textfield2" class="Vinput" type="text" value="<?php echo $row_views['place']; ?>"></td>
      <td><a href="Save.php"><img src="images/Save.png" alt="Save" width="15" height="15">Save Incident </a></td>
    </tr>
    <tr>
      <td>Incident type </td>
      <td><input name="textfield3" class="Vinput" type="text" value="<?php echo $row_views['type']; ?>"></td>
      <td><a href="Delete.php"><img src="images/Delete.png" alt="Delete" width="15" height="15">Delete Incident </a></td>
    </tr>
    <tr>
      <td>Reporter's Name </td>
      <td><input type="text" class="Vinput" name="textfield4"></td>
      <td><a href="Find.php"><img src="images/Find.png" alt="Find" width="15" height="15">Find Incident </a></td>
    </tr>
    <tr>
      <td>Phone Number </td>
      <td><input type="text" class="Vinput" name="textfield5"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
</form>

<table style="background:#0066FF;" width="100%" border="0">
  <tr>
    <td><table width="100%" style="border:2px solid #999999;" border="0">
      <tr>
        <td colspan="2" style="border:1px solid #000; border-radius:1px;">Time</td>
        </tr>
      <tr>
        <td>Time Slot </td>
        <td style="background-color:#FFFFFF;"><?php echo $row_views['time']; ?></td>
      </tr>
      <tr>
        <td>Date</td>
        <td style="background-color:#FFFFFF;"><?php echo $row_views['Timestamp']; ?></td>
      </tr>
	  <tr>
        <td colspan="2" style="border:1px solid #000; border-radius:1px;">Incident</td>
        </tr>
      <tr>
        <td >Type</td>
        <td style="background-color:#FFFFFF;"><?php echo $row_views['type']; ?></td>
      </tr>
      <tr>
	  <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </td>
  </tr>
</table>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysql_free_result($views);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>
