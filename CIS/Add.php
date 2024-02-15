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
	 <input class="btn btn-default" type="submit" value="Add Incident">
	</div></div>
</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>
