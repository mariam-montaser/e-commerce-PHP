<?php

ob_start();
session_start();

$pageTitle = 'Show Item';

include 'intial.php';

// check if GET request userid is numeric and the value of it
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
// select all data with this id
$stmt = $con->prepare("SELECT items.*, categories.name AS category_name, name.username AS member
						FROM items
						INNER JOIN categories
						ON categories.ID = items.cat_ID
						INNER JOIN name
						ON name.userID = items.member_ID 
						WHERE itemID=?
						AND approve = 1");
// execute the query
$stmt->execute(array($itemid));
// the row count
$count = $stmt->rowCount();

if ($count > 0) {

	// fetch the data
	$item = $stmt->fetch();



	?>

	<h1 class="text-center"><?php echo $item['name']; ?></h1>
	<div class="container">
		<!--start items info-->
		<div class="row">
			<div class="col-md-3">
				<img class="img-responsive img-thumbnail center-block" src="images.png">
			</div>
			<div class="col-md-9 item-info">
				<h2><?php echo $item['name']; ?></h2>
				<p><?php echo $item['description']; ?></p>
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Add In</span>: <?php echo $item['add_date']; ?>
					</li>
					<li>
						<i class="fa fa-money fa-fw"></i>
						<span>Price</span>: <?php echo $item['price']; ?>
					</li>
					<li>
						<i class="fa fa-building fa-fw"></i>
						<span>Made In</span>: <?php echo $item['country_made']; ?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Category</span>: <a href="categories.php?catid=<?php echo $item['cat_ID']?>"><?php echo $item['category_name']; ?></a>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Added By</span>: <a href='#'><?php echo $item['member']; ?></a>
					</li>
					<li class="tags">
						<i class="fa fa-user fa-fw"></i>
						<span>Tags</span>:
						<?php

							$tags = explode(',', $item['tags']);
							foreach ($tags as $tag) {
								$tag = str_replace(" ", "", $tag);
								$lowertag = strtolower($tag);
								if (!empty($tag)) {
								echo "<a href='tags.php?name=" . $lowertag . "'>" . $tag . "</a>";
								}
							}

						?>
					</li>
				</ul>
			</div>
		</div>
		<hr class="custom">
		<!--start add comment-->
		<?php if (isset($_SESSION['user'])) {?>
		<div class="row">
			<div class="col-md-offset-3">
				<div class="add-comment">
					<h3>Add Your Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] . "?itemid=" . $item['itemID']; ?>" method="POST">
						<textarea name="comment" required></textarea>
						<input class="btn btn-primary" type="submit" name="" value="Add Comment" />
					</form>
					<?php

						if($_SERVER['REQUEST_METHOD'] == 'POST') {

							$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
							$itemId  = $item['itemID'];
							$userId  = $_SESSION['uid'];

							if (!empty($comment)) {

								$stmt = $con->prepare("INSERT INTO comments(comment, status, comment_date, item_id, user_id)
														VALUES(:COMMENT, 0, now(), :ITEM_ID, :USER_ID)");

								$stmt->execute(array(

									'COMMENT' => $comment,
									'ITEM_ID' => $itemId,
									'USER_ID' => $userId

								));

								if ($stmt) { echo "<div class='alert alert-success'>Comment Added</div>";}

							} else {

								echo "<div class='alert alert-danger'>You Should Write A Comment First</div>";
							}
						}

					?>
				</div>
			</div>
		</div>
		<?php } else {
			echo "<a href='login.php'>Login</a> Or Register To Add Comment";
		}?>
		<hr class="custom">
		<!--start member comment-->
		<?php //get comments from DB
			$getcomments = $con->prepare("SELECT comments.*, name.username AS member
											FROM comments
											INNER JOIN name
											ON comments.user_id = name.userID
											WHERE item_id = ?
											AND status = 1
											ORDER BY c_id
											DESC");

			$getcomments->execute(array($item['itemID']));

			$comments = $getcomments->fetchAll();

			//display the comments

			foreach ($comments as $comment) {
				echo "<div class='comment-box'>";
					echo "<div class='row'>";
						echo "<div class='col-md-2 text-center'>";
							echo "<img class='img-responsive img-thumbnail img-circle center-block' src='images.png' />";
						    echo $comment['member'];
						echo "</div>";
						echo "<div class='col-md-10'>";
							echo "<p class='lead'>" . $comment['comment'] . "</p>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<hr class='custom'>";
			}


		?>

	</div>
	
<?php
} else {
	echo "<div class='container'>";
		echo "<div class='alert alert-danger'>There's No Such ID Or This Item Is Waiting For Approval</div>";
	echo "</div>";
}
include $tpl . 'footer.php';
ob_end_flush();
?>