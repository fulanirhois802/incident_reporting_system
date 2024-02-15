<?php require_once('Connections/db_connect.php'); ?>
<?php
$colname_findplace = "-1";
if (isset($_POST['find'])) {
  $colname_findplace = (get_magic_quotes_gpc()) ? $_POST['find'] : addslashes($_POST['find']);
}
mysqli_select_db($db_connect, $database_db_connect );
$query_findplace = sprintf("SELECT * FROM incidents WHERE place LIKE '%%%s%%' ORDER BY place DESC", $colname_findplace);
$findplace = mysqli_query($db_connect, $query_findplace) or die(mysql_error());
$row_findplace = mysqli_fetch_assoc($findplace);
$totalRows_findplace = mysqli_num_rows($findplace);
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
        <td><i>Time</i></td>
        <td><i>Place</i></td>
      </tr>
      <?php do { ?>
        <tr style="background-color:#FFCC66;">
		 <td><?php echo $row_findplace['place']; ?></td> 
		  <td><?php echo $row_findplace['type']; ?> </td>
          <td><?php echo $row_findplace['time']; ?></td>
         
		  <td><a href="view.php?userid=<?php echo $row_findplace['inc_id']; ?>">View</a></td>
        </tr>
		<tr bgcolor="#FFFFFF"></tr>
        <?php } while ($row_findplace = mysqli_fetch_assoc($findplace)); ?>
    </table>
	

    

   
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
<?php
mysqli_free_result($findplace);
?>
