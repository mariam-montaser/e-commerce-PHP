<?php include 'intial.php' ?>


<div class="container">
	<h1 class="text-center">Show Category</h1>
	<div class="row">
		<?php
			if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {

				$category = intval($_GET['catid']);

				$items = getAll("*", "items", "WHERE cat_ID = $category", "AND approve = 1", "itemID");
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
				echo "you must add category";
			}
		?>
	</div>
</div>


<?php include $tpl . 'footer.php' ?>