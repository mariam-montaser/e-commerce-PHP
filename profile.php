<?php

ob_start();
session_start();

$pageTitle = 'Profile';

include 'intial.php';
//echo $session;

if (isset($_SESSION['user'])) {

	//get the data from DB
	$getUser = $con->prepare("SELECT * FROM name WHERE username = ?");

	$getUser->execute(array($session));

	$info = $getUser->fetch();
	$userId = $info['userID'];
?>

<h1 class="text-center">My Profile</h1>
<div class="info block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
			 	<ul class="list-unstyled">
			 		<li>
			 			<i class="fa fa-unlock-alt fa-fw"></i>
			 			<span>Name</span>: <?php echo $info['username'];?>
			 		</li>
				 	<li>
				 		<i class="fa fa-envelope-o fa-fw"></i>
				 		<span>Email</span>: <?php echo $info['email'];?>
				 	</li>
				 	<li>
				 		<i class="fa fa-user fa-fw"></i>
				 		<span>Full Name</span>: <?php echo $info['fullname'];?>
				 	</li>
				 	<li>
				 		<i class="fa fa-calendar fa-fw"></i>
				 		<span>Register Date</span>: <?php echo $info['date'];?>
				 	</li>
				 	<li>
				 		<i class="fa fa-tags fa-fw"></i>
				 		<span>Favorite Category</span>:
				 	</li>
			 	</ul>
			 	<a href="#" class="btn btn-default">Edit</a>
			</div>
		</div>
	</div>
</div>

<div id="my-ads" class="ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Items</div>
			<div class="panel-body">
				<?php 
					$items = getAll("*", "items", "WHERE member_ID = {$userId}", "", "itemID");
					//$items = getItems('member_ID', $info['userID'], 1);//num 1 is to show unapproved ads also
					//echo $_GET['catid'];

					if (!empty($items)) {
						echo "<div class='container'>";
						foreach ($items as $item) {

							echo "<div class='col-sm-6 col-md-3'>";
								echo "<div class='thumbnail item-box'>";
								if ($item['approve'] == 0) {

									echo "<span class='approve'>Waiting Approval</span>";
								}
									echo "<span class='price'>$" . $item['price'] . "</span>";
									echo "<img src='images.png' class='img-responsive' />";
									echo "<div class='caption'>";
										echo "<h3><a href='items.php?itemid=" . $item['itemID'] . "'>" . $item['name'] . "</a></h3>";
										echo "<p>" . $item['description'] . "</p>";
										echo "<div class='date'>" . $item['add_date'] . "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						}
						echo "</div>";
					} else {

						echo "There's No Ads To Show. <a href='newad.php'>New Ad</a>";

					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="comment block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
				<?php 
					$comments = getAll("comment", "comments", "WHERE user_id = $userId", "", "c_id");

					if (!empty($comments)) { 

						foreach ($comments as $comment) {

							echo "<p>" . $comment['comment'] . "</p>";
						}

					} else {

						echo "There's No Comments To Show";
					}

				?>
			</div>
		</div>
	</div>
</div>

	
<?php
} else {

	header('location: login.php');

	exit();
}

include $tpl . 'footer.php';
ob_end_flush();
?>