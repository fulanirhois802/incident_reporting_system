<?php require_once('Connections/db_connect.php'); ?>
<?php
mysqli_select_db($db_connect, $database_db_connect );
$query_login = "SELECT username, password FROM registration";
$login = mysqli_query($db_connect, $query_login) or die(mysql_error());
$row_login = mysqli_fetch_assoc($login);
$totalRows_login = mysqli_num_rows($login);
?><?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password= md5($_POST['password']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "report.php";
  $MM_redirectLoginFailed = "logfail.php";
  $MM_redirecttoReferrer = false;
  mysqli_select_db($db_connect, $database_db_connect);
  
  $LoginRS__query=sprintf("SELECT username, password FROM registration WHERE username='%s' AND password='%s'",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysqli_query( $db_connect, $LoginRS__query) or die(mysql_error());
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<head>
<title>Login Fail: Incorrect Credentials</title>
<link rel="stylesheet" href="Stylesheet/styles.css" type="text/css"></head>
	<?php include 'Adm-head.php'; ?>
<!--START main body-->
<body>
<?php include 'Adm-menu2.php'; ?>
<!--Menu header-->
  	
<!--Content body-->
<div class="container">
	<!--START Login Form-->
  <h3><p style="font-family:Georgia, 'Times New Roman', Times, serif;" class="text-danger"><i class="glyphicon glyphicon-warning-sign"></i>Wrong Username &/Password</p></h3>
  
	<form action="<?php echo $loginFormAction; ?>" method="POST" name="login_form">
<h3><p class="text-info">Please Login below as a User</p></h3>
<div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cardNumber">Username</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span>Username </span></div>
                                            <input name="username" class="form-control" type="text" required="" placeholder="Your login username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                          
                          <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cardNumber">Password</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span>Password </span></div>
                                            <input name="password" class="form-control" type="password" required="" placeholder="Your login password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                          <div class="row">
                                <div class="col-xs-4">
                                <div class="form-group"> 
									<input type="submit" value="Login" class="btn btn-danger">
									</div>
                           </div>
                        </div>
                    </form>    

</div><!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html><?php
mysqli_free_result($login);
?>
