<?php

session_start();
$pageTitle = 'Login';

if (isset($_SESSION['user'])) {
	header('location:index.php');// redirect to homepage
}

include 'intial.php';

	// check if come with post request

	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		if (isset($_POST['login'])) {

			$user = $_POST['name'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			//echo $user . $pass;

			// check if the user exist in db

			$stmt = $con->prepare('SELECT userID, username, password 
									FROM name 
									WHERE username=? 
									AND password=?');
			$stmt->execute(array($user, $hashedPass));

			$get =$stmt->fetch();

			$count = $stmt->rowCount();

			//if count > 0 

			if ($count > 0) { 
				$_SESSION['user'] = $user; // register session name 

				$_SESSION['uid'] = $get['userID']; //register session user id 

				header('location:index.php');//redirect to homepage page

				exist(); 
			}

		} else {

			//$theError = $_POST['name'];
			$formErrors = array();

			$user = $_POST['name'];
			$pass = $_POST['password'];
			$pass2 = $_POST['password2'];
			$email = $_POST['email'];

			//username validate
			if (isset($user)) {

				$filterdName = filter_var($user, FILTER_SANITIZE_STRING);

				if (strlen($filterdName) < 4) {

					$formErrors[] = 'Name Must Be More Than 4 Letters';
				}

			}

			//password validate
			if (isset($pass) && isset($pass2)) {

				if (empty($pass)) {

					$formErrors[] = 'Sorry Password Can\'t Be Empty';

				}

				if (sha1($pass) !== sha1($pass2)) {

					$formErrors[] = 'Sorry Password Dosen\'t Match';
				}

			}

			//email validate
			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'Email Is Not Valid';
				}

			}

			//insert the data in database

			// if there's no errors

			if (empty($formErrors)) {

				// check if name exist in database

				$check = checkItem('username', 'name', $user);

				if ($check ==1) {

					$formErrors[] = 'Sorry This Username Is Exist'; 

				} else {

					// insert the data in database
					$stmt = $con->prepare("INSERT INTO name(username, email, password, regstatus, date) VALUES(:USER, :MAIL, :PASS, 0, now())");
	  				$stmt->execute(array(
	  					'USER' => $user,
	  					'MAIL' => $email, 
	  					'PASS' => sha1($pass) 
	  				));

	  				// success message
	  				$successMsg = 'Congrats you are registered successfully' ;

				}

			}	

		}
		
	}



?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
	</h1>
	<!--login form-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-holder">
			<input type="text" class="form-control" name="name" autocomplete="off" placeholder="Type your Name" required> 
		</div>
		<div class="input-holder">
			<input type="password" class="form-control" name="password" autocomplete="new-password" placeholder="Type password" required>
		</div>
		<input type="submit" class="btn btn-primary btn-block" name="login" value="Login" >
	</form>
	<!--signup form-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-holder">
			<input pattern=".{4, 10}" title="Username Must Be Between 4 & 10 chars" type="text" class="form-control" name="name" autocomplete="off" placeholder="Type your Name" required>
		</div>
		<div class="input-holder">
			<input type="email" class="form-control" name="email" autocomplete="off" placeholder="Type your Email" required>
		</div>
		<div class="input-holder">
			<input minlength="4" type="password" class="form-control" name="password" autocomplete="new-password" placeholder="Type password" required>
		</div>
		<div class="input-holder">
			<input minlength="4" type="password" class="form-control" name="password2" autocomplete="new-password" placeholder="Type password again" required>
		</div>
		<input type="submit" class="btn btn-success btn-block" name="signup" value="Signup" >
	</form>
	<div class="msg text-center">
		<?php

			if (!empty($formErrors)) {
				foreach ($formErrors as $error) {
					echo "<div class='the-msg error'>" . $error . "</div>";
				}
			}

			if (isset($successMsg)) {

				echo "<div class='the-msg success'>" . $successMsg . "</div>";
			}

		?>
	</div>
</div>

<?php include $tpl . 'footer.php' ?>