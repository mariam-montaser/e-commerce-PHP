<?php include 'intial.php' ?>


<div class="container">

	<div class="row">
		<?php
			if (isset($_GET['name'])) {

				$tag = $_GET['name'];

				echo '<h1 class="text-center">' . $tag . '</h1>';

				$items = getAll("*", "items", "WHERE tags LIKE '%$tag%'", "AND approve = 1", "itemID");
				//$items = getItems('cat_ID', $_GET['catid']);
				//echo $_GET['catid'];
				foreach ($items as $item) {

					echo "<div class='col-sm-6 col-md-3'>";
						echo "<div class='thumbnail item-box'>";
							echo "<span class='price'>" . $item['price'] . "</span>";
							echo "<img src='images.png' class='img-responsive' />";
							echo "<div class='caption'>";
								echo "<h3><a href='items.php?itemid=" . $item['itemID'] . "'>" . $item['name'] . "</a></h3>";
								echo "<p>" . $item['description'] . "</p>";
								echo "<div class='date'>" . $item['add_date'] . "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
			} else {
				echo "you must add tag";
			}
		?>
	</div>
</div>


<?php include $tpl . 'footer.php' ?>