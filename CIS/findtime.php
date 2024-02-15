<?php require_once('Connections/db_connect.php'); ?>
<?php
$colname_search = "-1";
if (isset($_POST['find'])) {
  $colname_search = (get_magic_quotes_gpc()) ? $_POST['find'] : addslashes($_POST['find']);
}
mysqli_select_db($db_connect, $database_db_connect);
$query_search = sprintf("SELECT * FROM incidents WHERE `Timestamp` LIKE '%%%s%%' ORDER BY `Timestamp` DESC", $colname_search);
$search = mysqli_query($db_connect, $query_search) or die(mysql_error());
$row_search = mysqli_fetch_assoc($search);
$totalRows_search = mysqli_num_rows($search);
?><!DOCTYPE html>
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
        <td><i>Timestamp</i></td>
        <td><i>Place</i></td>
      </tr>
      <?php do { ?>
        <tr style="background-color:#FFCC66;">
<td><?php echo $row_search['Timestamp']; ?></td>
<td><?php echo $row_search['type']; ?></td>
          <td><?php echo $row_search['place']; ?></td> 
		  <td><a href="view.php?userid=<?php echo $row_search['inc_id']; ?>">View</a></td>
        </tr>
		<tr bgcolor="#FFFFFF"></tr>
        <?php } while ($row_search = mysqli_fetch_assoc($search)); ?>
    </table>
	
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
<?php
mysqli_free_result($search);
?>
