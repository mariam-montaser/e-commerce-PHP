<?php

// members page 
// you can add admin | delete | edit

ob_start();

session_start();

	$pageTitle = 'Members';

  	if (isset($_SESSION['username'])) {
  		
  		include 'intial.php';
  		
  		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  		//start manage page 
  		if ($do == "Manage") {

  			$query = '';

  			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
  				$query = 'AND regstatus = 0';
  			}

			$stmt = $con->prepare("SELECT * FROM name WHERE groupID != 1 $query ORDER BY userID DESC");
			$stmt->execute();
 
			// assign to var
			$rows = $stmt->fetchAll();

  			?>

  			<h1 class="text-center">Manage Members</h1>
  			<div class="container">
  				<?php if (!empty($rows)) { ?>
  				<div class="table-responsive">
  					<table class="table main-table table-bordered text-center">
  						<tr>
  							<td>#ID</td>
  							<td>Avatar</td>
  							<td>Username</td>
  							<td>Email</td>
  							<td>Full Name</td>
  							<td>Registerd Date</td>
  							<td>Control</td>
  						</tr>

  						<?php 

  						foreach ($rows as $row) {
  							echo '<tr>';
  								echo '<td>'. $row['userID'] . '</td>';
  								echo '<td>';

  								if (empty($row['avatar'])){
  									echo '<img src="images.png" alt="avatar" />';
  								} else {
  									echo '<img src="upload/avatar/'. $row['avatar'] . '" alt="avatar" />';
  								}
  								
  								echo '</td>';
  								echo '<td>'. $row['username'] . '</td>';
  								echo '<td>'. $row['email'] . '</td>';
  								echo '<td>'. $row['fullname'] . '</td>';
  								echo '<td>'. $row['date'] .'</td>';
  								echo '<td>
  									<a href="members.php?do=Edit&userid=' . $row['userID'] . '" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a>
  									<a href="members.php?do=Delete&userid=' . $row['userID'] . '" class=" confirm  btn btn-danger"><i class="fa fa-close"></i>Delete</a>';
  									if ($row['regstatus'] == 0) {
  										echo '<a href="members.php?do=Activate&userid=' . $row['userID'] . '" class=" activate btn btn-info"><i class="fa fa-check"></i>Activate</a>';
  									}
  								echo '</td>';
  							echo '</tr>';
  						}

  						?>
  						
  					</table>
  				</div>
  				<?php 
  				} else {

		  			echo "<div class='alert alert-danger'>There\'s No Record To Show</div>";

		  		} ?>
  				<a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>Add New Member</a>
  			</div>

  	<?php
  		} elseif ($do == "Add") {//add page?>

  			<h1 class="text-center">Add New Member</h1>
  			<div class="container">
  				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
  					<!--username-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Username</label>
  						<div class="col-sm-10">
  							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="username to login to shop">
  						</div>
  					</div>
  					<!--password-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Password</label>
  						<div class="col-sm-10">
  							<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="must be difficult">
  							<i class="show-pass fa fa-eye fa-lg"></i> 
  						</div>
  					</div>
  					<!--email-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Email</label>
  						<div class="col-sm-10">
  							<input type="email" name="email" class="form-control" required="required" placeholder="Email must be valid">
  						</div>
  					</div>
  					<!--fullname-->
  					<div class="form-group form-group-lg ">
  						<label class="control-label col-sm-2">Full Name</label>
  						<div class="col-sm-10">
  							<input type="text" name="fullname" class="form-control" required="required" placeholder="appear in profile page">
  						</div>
  					</div>
  					<!--avatar-->
  					<div class="form-group form-group-lg ">
  						<label class="control-label col-sm-2">User's Image</label>
  						<div class="col-sm-10">
  							<input type="file" name="avatar" class="form-control" placeholder="appear in profile page" required>
  						</div>
  					</div>
  					<!--submit-->
  					<div class="form-group form-group-lg">
  						<div class="col-sm-10 col-sm-offset-2">
  							<input type="submit" value="Add New Member" class="btn btn-primary btn-lg">
  						</div>
  					</div>
  				</form>
  			</div>
  		<?php

  		} elseif ($do == "Insert") {//insert page

  			//echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['fullname'];
  			//update page
  			
  			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  				echo '<h1 class="text-center">Insert New Member</h1>';
  				echo "<div class='container' >";

  					//upload variables

  					//$avatar     = $_FILES['avatar'];
  					$avatarName = $_FILES['avatar']['name'];
  					$avatarSize = $_FILES['avatar']['size'];
  					$avatarTmp  = $_FILES['avatar']['tmp_name'];
  					$avatarType = $_FILES['avatar']['type'];

  					//allowed avatar extention

  					$allowedExtention = array('jpg', 'png', 'gif');

  					//get the extention

  					$avatarExtention = strtolower(end(explode('.', $avatarName)));
  					
	  				//get vars from the form

	  				$username = $_POST['username'];
	  				$password = $_POST['password'];
	  				$email 	  = $_POST['email'];
	  				$fullname = $_POST['fullname'];

	  				$hashedpass = sha1($_POST['password']);

	  				//echo $id . $username . $email . $fullname;
	  			
	  				// validate the form
	  				$formErrors = array();

	  				if (strlen($username) < 4){

	  					$formErrors[] = 'Username can\'t be less than <strong>4</strong>.';
	  				}

	  				if (strlen($username) > 20){

	  					$formErrors[] = 'Username can\'t be more than <strong>20</strong>.';
	  				}

	  				if (empty($username)) {

	  					$formErrors[] = 'Username field can\'t be <strong>empty</strong>.';
	  				}

	  				if (empty($password)) {

	  					$formErrors[] = 'Password field can\'t be <strong>empty</strong>.';
	  				}

	  				if (empty($email)) {

	  					$formErrors[] = 'Email field can\'t be <strong>empty</strong>.';
	  				}

	  				if (empty($fullname)) {

	  					$formErrors[] = 'Full name field can\'t be <strong>empty</strong>. ';
	  				}

	  				if (empty($avatarName)) {

	  					$formErrors[] = 'Avatar field can\'t be <strong>empty</strong>. ';
	  				}

	  				if (!empty($avatarName) && !in_array($avatarExtention, $allowedExtention)) {

  						$formErrors[] = 'Image extention Is Not <strong>allowed</strong>. ';
  					}

  					if ($avatarSize > 4194304) {

	  					$formErrors[] = 'Avatar size can\'t be larger than <strong>4MB</strong>. ';
	  				}

	  				foreach ($formErrors as $error) {

	  					echo '<div class= "alert alert-danger" >' . $error . '</div> <br /> ';
	  					
	  				}

	  				//insert the data in database

	  				// if there's no errors

	  				if (empty($formErrors)) {

	  					
	  				}

	  				if (empty($formErrors)) {

	  					//add random to the avatar name
	  					$avatar = rand(0, 1000000) . "_" . $avatarName;
	  					//move avatar to upload folder
	  					move_uploaded_file($avatarTmp, "upload\avatar\\" . $avatar);
 
	  					// check if name exist in database

	  					$check = checkItem('username', 'name', $username);

	  					if ($check ==1) {

	  						$theMessage = 'div class="alert alert-danger">this username is exist</div>';
	  						redirectPage($theMessage, 'back');

	  					} else {

	  						// insert the data in database
		  					$stmt = $con->prepare("INSERT INTO name(username, email, fullname, avatar, password, regstatus, date) VALUES(:USER, :MAIL, :FULL, :AVATAR, :PASS, 1, now())");
			  				$stmt->execute(array(
			  					'USER' 	 => $username,
			  					'MAIL'   => $email, 
			  					'FULL'   => $fullname,
			  					'AVATAR' => $avatar,
			  					'PASS'   => $hashedpass 
			  				));

			  				// success message
			  				$theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record inserted </div>';
			  				redirectPage($theMessage, 'back' );

	  					}

	  				}

	  			} else {

	  				echo "<div class='container'>";
	  				$theMessage =  "<div class='alert alert-danger'>you not allowed to browse this page directly</div>";
	  				redirectPage($theMessage, 'back', 6);
	  				echo "</div>";
	  			}

  			echo "</div>";
  		

  		} elseif ($do == "Edit") {//edit page 

  			//echo "welcome to edit page and your ID " . $_GET['userid'];

  			// check if GET request userid is numeric and the value of it
  			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
  			// select all data with this id
  			$stmt = $con->prepare('SELECT * FROM name WHERE userid=? LIMIT 1');
  			// execute the query
			$stmt->execute(array($userid));
			// fetch the data
			$row = $stmt->fetch();
			// the row count
			$count = $stmt->rowCount();

			// if there's such id
			if ($count > 0) {
				//echo "good";?>

	  			<h1 class="text-center">Edit Member</h1>
	  			<div class="container">
	  				<form class="form-horizontal" action="?do=Update" method="POST">
	  					<!--userid-->
	  					<input type="hidden" name="userid" value="<?php echo $userid; ?>">
	  					<!--username-->
	  					<div class="form-group form-group-lg">
	  						<label class="control-label col-sm-2">Username</label>
	  						<div class="col-sm-10">
	  							<input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" autocomplete="off" required="required">
	  						</div>
	  					</div>
	  					<!--password-->
	  					<div class="form-group form-group-lg">
	  						<label class="control-label col-sm-2">Password</label>
	  						<div class="col-sm-10">
	  							<input type="hidden" name="oldpassword" value="<?php echo $row['password']; ?>">
	  							<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave this blank if you don't want to change">
	  						</div>
	  					</div>
	  					<!--email-->
	  					<div class="form-group form-group-lg">
	  						<label class="control-label col-sm-2">Email</label>
	  						<div class="col-sm-10">
	  							<input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control" required="required">
	  						</div>
	  					</div>
	  					<!--fullname-->
	  					<div class="form-group form-group-lg ">
	  						<label class="control-label col-sm-2">Full Name</label>
	  						<div class="col-sm-10">
	  							<input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" class="form-control" required="required">
	  						</div>
	  					</div>
	  					<!--submit-->
	  					<div class="form-group form-group-lg">
	  						<div class="col-sm-10 col-sm-offset-2">
	  							<input type="submit" value="Save" class="btn btn-primary btn-lg">
	  						</div>
	  					</div>
	  				</form>
	  			</div>
  		<?php
  			// else if there's such id
  			} else {

  				echo "<div class='container'>";
				$theMessage = "<div class='alert alert-danger'>There's No Such ID</div>";
				redirectPage($theMessage);
				echo "</div>";
				//header('location:index.php');
			}


  		} elseif ($do == "Update") {//update page

  			echo '<h1 class="text-center">Update Member</h1>';
			echo "<div class='container' >";
  			
  			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	  				//get vars from the form

	  				$id = $_POST['userid'];
	  				$username = $_POST['username'];
	  				//$password = $_POST['password'];
	  				$email = $_POST['email'];
	  				$fullname = $_POST['fullname'];

	  				//echo $id . $username . $email . $fullname;

	  				// password update
	  				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);;
	  			
	  				// validate the form
	  				$formErrors = array();

	  				if (strlen($username) < 4){

	  					$formErrors[] = 'Username can\'t be less than <strong>4</strong>.';
	  				}

	  				if (strlen($username) > 20){

	  					$formErrors[] = 'Username can\'t be more than <strong>20</strong>.';
	  				}

	  				if (empty($username)) {

	  					$formErrors[] = 'Username field can\'t be <strong>empty</strong>.';
	  				}

	  				if (empty($email)) {

	  					$formErrors[] = 'Email field can\'t be <strong>empty</strong>.';
	  				}

	  				if (empty($fullname)) {

	  					$formErrors[] = 'Full name field can\'t be <strong>empty</strong>. ';
	  				}

	  				foreach ($formErrors as $error) {

	  					echo '<div class= "alert alert-danger" >' . $error . '</div> <br /> ';
	  				}

	  				//update the data

	  				// if there's no errors

	  				if (empty($formErrors)) {

	  					$stmt0 = $con->prepare("SELECT * FROM name WHERE username = ? AND userID != ?");
	  					$stmt0->execute(array($username, $id));
	  					$count = $stmt0->rowCount();

	  					if ($count == 1) {

	  						$theMessage = "<div class='alert alert-danger'>Sorry This User Is exist</div>";
	  						redirectPage($theMessage, 'back');

	  					} else {

	  						$stmt = $con->prepare('UPDATE name SET username = ?, email = ?, fullname = ?, password = ? WHERE userid = ?');
			  				$stmt->execute(array($username, $email, $fullname, $pass, $id));

			  				$theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record updated. </div>';
			  				redirectPage($theMessage, 'back');
	  					}
	  				} 
	  				
	 
	  			} else {

	  				$theMessage = "<div class='alert alert-danger'>you not allowed to browse this page directly</div>";
	  				redirectPage($theMessage);
	  			}

			echo "</div>";
  		 
  		} elseif ($do == "Delete") {//delete member page

  			echo '<h1 class="text-center">Delete Member</h1>';
  			echo "<div class='container' >";

  			// check if GET request userid is numeric and the value of it
  			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
  			// select all data with this id
  			//$stmt = $con->prepare('SELECT * FROM name WHERE userid=? LIMIT 1');
  			// execute the query
			//$stmt->execute(array($userid));
			// the row count
			$check = checkItem('userid', 'name', $userid);

			// if there's such id
			if ($check > 0) {  

				$stmt = $con->prepare('DELETE FROM name WHERE userID = :ID');
				$stmt->bindParam('ID', $userid);
				$stmt->execute();
				$theMessage = "<div class='alert alert-success' >" . $check . "record deleted. </div>";
				redirectPage($theMessage, 'back');

			} else {

				$theMessage = "<div class='alert alert-danger'>There's no such ID</div>";
				redirectPage($theMessage);
			}

			echo "</div>";
  		
  		} elseif ($do == "Activate") {//Activate page

  			echo '<h1 class="text-center">Activate Member</h1>';
  			echo "<div class='container' >";

  			// check if GET request userid is numeric and the value of it
  			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
  			
			// the row count
			$check = checkItem('userid', 'name', $userid);

			// if there's such id
			if ($check > 0) {  

				$stmt = $con->prepare("UPDATE name SET regstatus = 1 WHERE userID = ?");
				//$stmt->bindParam('ID', $userid);
				$stmt->execute(array($userid));
				$theMessage = "<div class='alert alert-success' >" . $check . "record Activated. </div>";
				redirectPage($theMessage, 'back');

			} else {

				$theMessage = "<div class='alert alert-danger'>There's no such ID</div>";
				redirectPage($theMessage);
			}

			echo "</div>";
  		}



  		include $tpl . 'footer.php';

  	} else {

  		//echo "you are not autherised";  

  		header('location:index.php');

  		exit();
  	} 

ob_end_flush();
?>