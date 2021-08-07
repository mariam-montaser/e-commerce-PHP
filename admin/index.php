  <?php

  	session_start();
  	$noNavbar = '';
  	$pageTitle = 'Login';
  	if (isset($_SESSION['username'])) {
  		header('location:dashboard.php');
  	}

	include 'intial.php';

	

	// check if come with post request

	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password);

		// check if the user exist in db

		$stmt = $con->prepare('SELECT userID, username, password FROM name WHERE username=? AND password=? AND groupID=1 LIMIT 1');
		$stmt->execute(array($username, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		//if count > 0 

		if ($count > 0) { 
			$_SESSION['username'] = $username; // register session name 
			$_SESSION['ID'] = $row['userID']; // register session ID
			header('location:dashboard.php');//redirect to dashboard page
			exist(); 
			//echo 'welcome ' . $username;
		}
	}

?>
	<!--login form-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<h3 class="text-center">Admin Login</h3>		
		<input class="form-control" type="text" name="user" placeholder="username" autocomplete="off" />
		<input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" />
		<input class="btn btn-primary btn-block" type="submit" value="Login" />

	</form>

	<!-- welcome to index
	<div class="btn btn-danger btn-block">
		test
	</div>
	<i class="fa fa-home fa-5x"></i> 

	<?php //echo lang('message'). ' ' . lang('admin'); ?> -->

<?php

	include $tpl . 'footer.php';

?>   