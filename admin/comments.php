<?php

// comments page 
// you can manage comments | delete | edit ...

ob_start();

session_start();

	$pageTitle = 'Comments';

  	if (isset($_SESSION['username'])) {
  		
  		include 'intial.php';
  		
  		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  		//start manage page 
  		if ($do == "Manage") {

			$stmt = $con->prepare("SELECT
										comments.*, items.name AS item_name, name.username
									FROM comments
									INNER JOIN items
									ON items.itemID = comments.item_id
									INNER JOIN name
									ON name.userID = comments.user_id
									ORDER BY c_id DESC");
			$stmt->execute();

			// assign to var
			$rows = $stmt->fetchAll();

  			?>

  			<h1 class="text-center">Manage Comments</h1>
  			<div class="container">
  			<?php if (!empty($rows)) { ?>
  				<div class="table-responsive">
  					<table class="table main-table table-bordered text-center">
  						<tr>
  							<td>#ID</td>
  							<td>Comment</td>
  							<td>Item Name</td>
  							<td>User Name</td>
  							<td>Added Date</td>
  							<td>Control</td>
  						</tr>

  						<?php 

  						foreach ($rows as $row) {
  							echo '<tr>';
  								echo '<td>'. $row['c_id'] . '</td>';
  								echo '<td>'. $row['comment'] . '</td>';
  								echo '<td>'. $row['item_name'] . '</td>';
  								echo '<td>'. $row['username'] . '</td>';
  								echo '<td>'. $row['comment_date'] .'</td>';
  								echo '<td>
  									<a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a>
  									<a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class=" confirm  btn btn-danger"><i class="fa fa-close"></i>Delete</a>';
  									if ($row['status'] == 0) {
  										echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class=" activate btn btn-info"><i class="fa fa-check"></i>Approve</a>';
  									}
  								echo '</td>';
  							echo '</tr>';
  						}

  				?>
  						
  					</table>
  				</div>
  				<?php
  				}else {
	
			  		echo "<div class='alert alert-danger'>There\'s No Record To Show</div>";

			  		}
  				 ?>
  			</div>
  		<?php
  		
  		} elseif ($do == "Edit") {//edit page 

  			//echo "welcome to edit page and your ID " . $_GET['userid'];

  			// check if GET request comment id is numeric and the value of it
  			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
  			// select all data with this id
  			$stmt = $con->prepare('SELECT * FROM comments WHERE c_id=?');
  			// execute the query
			$stmt->execute(array($comid));
			// fetch the data
			$row = $stmt->fetch();
			// the row count
			$count = $stmt->rowCount();

			// if there's such id
			if ($count > 0) {
				//echo "good";?>

	  			<h1 class="text-center">Edit Comment</h1>
	  			<div class="container">
	  				<form class="form-horizontal" action="?do=Update" method="POST">
	  					<!--comment id-->
	  					<input type="hidden" name="comid" value="<?php echo $comid; ?>">
	  					<!--Comment-->
	  					<div class="form-group form-group-lg">
	  						<label class="control-label col-sm-2">Comment</label>
	  						<div class="col-sm-10">
	  							<textarea name="comment" class="form-control"><?php echo $row['comment']; ?></textarea>
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

  			echo '<h1 class="text-center">Update Comment</h1>';
			echo "<div class='container' >";
  			
  			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	  				//get vars from the form

	  				$id = $_POST['comid'];
	  				$comment = $_POST['comment'];

	  				//update the data

  					$stmt = $con->prepare('UPDATE comments SET comment = ? WHERE c_id = ?');
	  				$stmt->execute(array($comment, $id));

	  				$theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record updated. </div>';
	  				redirectPage($theMessage, 'back');

	 
	  			} else {

	  				$theMessage = "<div class='alert alert-danger'>you not allowed to browse this page directly</div>";
	  				redirectPage($theMessage);
	  			}

			echo "</div>";
  		 
  		} elseif ($do == "Delete") {//delete member page

  			echo '<h1 class="text-center">Delete Comment</h1>';
  			echo "<div class='container' >";

  			// check if GET request comid is numeric and the value of it
  			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
  			// select all data with this id
  			//$stmt = $con->prepare('SELECT * FROM name WHERE userid=? LIMIT 1');
  			// execute the query
			//$stmt->execute(array($userid));
			// the row count
			$check = checkItem('c_id', 'comments', $comid);

			// if there's such id
			if ($check > 0) {  

				$stmt = $con->prepare('DELETE FROM comments WHERE c_id = :ID');
				$stmt->bindParam('ID', $comid);
				$stmt->execute();
				$theMessage = "<div class='alert alert-success' >" . $check . "record deleted. </div>";
				redirectPage($theMessage, 'back');

			} else {

				$theMessage = "<div class='alert alert-danger'>There's no such ID</div>";
				redirectPage($theMessage);
			}

			echo "</div>";
  		
  		} elseif ($do == "Approve") {//Approve page

  			echo '<h1 class="text-center">Approve Member</h1>';
  			echo "<div class='container' >";

  			// check if GET request comid is numeric and the value of it
  			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
  			
			// the row count
			$check = checkItem('c_id', 'comments', $comid);

			// if there's such id
			if ($check > 0) {  

				$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
				//$stmt->bindParam('ID', $userid);
				$stmt->execute(array($comid));
				$theMessage = "<div class='alert alert-success' >" . $check . "Comment Approved. </div>";
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