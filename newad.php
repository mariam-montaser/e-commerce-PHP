<?php

ob_start();
session_start();

$pageTitle = 'Add New Item';

include 'intial.php';
//echo $session;

if (isset($_SESSION['user'])) {

	//print_r($_SESSION);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$formErrors = array();

		$name 	  = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$desc 	  = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$price 	  = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
		$country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
		$category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
		$tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

		//echo $price;

		if (strlen($name) < 4) {

			$formErrors[] = 'Item Name Must Be At Least 4 Chars';
		}

		if (strlen($desc) < 10) {

			$formErrors[] = 'Item Description Must Be At Least 10 Chars';
		}

		if (empty($price)) {

			$formErrors[] = 'Item Price Must Be Not Empty';
		}

		if (strlen($country) < 2) {

			$formErrors[] = 'Item Country Must Be At Least 2 Chars';
		}

		if (empty($status)) {

			$formErrors[] = 'Item Status Must Be Not Empty';
		}

		if (empty($category)) {

			$formErrors[] = 'Item Category Must Be Not Empty';
		}

		//insert the data in database

        // if there's no errors

        if (empty($formErrors)) {

	        // insert the data in database
	        $stmt = $con->prepare("INSERT INTO items(name, description, price, country_made, status, add_date, cat_ID, member_ID, tags) 
	        						VALUES(:NAME, :DESCR, :PRICE, :COUNTRY, :STATUS, now(), :CAT, :MEMBER, :TAGS)");
	        $stmt->execute(array(
	          'NAME'    => $name,
	          'DESCR'   => $desc, 
	          'PRICE'   => $price,
	          'COUNTRY' => $country,
	          'STATUS'  => $status,
	          'CAT'     => $category,
	          'MEMBER'  => $_SESSION['uid'],
	          'TAGS'    => $tags

	        ));

        	// success message
        	if ($stmt) { $successMsg = "Item Added";}

        } 
		
	}



?>

	<h1 class="text-center"><?php echo $pageTitle; ?></h1>
	<div class="create-ad block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $pageTitle; ?></div>
				<div class="panel-body">
				 	<div class="row">
				 		<div class="col-md-8">
				 			<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
					            <!--name-->
					            <div class="form-group form-group-lg">
					              	<label class="control-label col-sm-2">Name</label>
					              	<div class="col-sm-10">
					                	<input pattern=".{4,}" title="Name Must Be At Least 4 Chars" type="text" name="name" class="form-control live" data-class="live-title" required placeholder="Name of new item">
					              	</div>
					            </div>
					            <!--descreption-->
					            <div class="form-group form-group-lg">
						            <label class="control-label col-sm-2">Description</label>
						            <div class="col-sm-10">
						                <input pattern=".{10,}" title="Name Must Be At Least 10 Chars" type="text" name="description" class="form-control live" data-class="live-desc" required="required" placeholder="describe the item">
						            </div>
					            </div>
					            <!--price-->
					            <div class="form-group form-group-lg">
					              	<label class="control-label col-sm-2">Price</label>
					              	<div class="col-sm-10">
					                	<input type="text" name="price" class="form-control live" data-class="live-price" required="required" placeholder="Price of the item">
					              	</div>
					            </div>
					            <!--country-->
					            <div class="form-group form-group-lg">
					              	<label class="control-label col-sm-2">Country</label>
					              	<div class="col-sm-10">
					                	<input type="text" name="country" class="form-control" required="required" placeholder="country of the item">
					              	</div>
					            </div>
					            <!--status-->
					            <div class="form-group form-group-lg">
					              	<label class="control-label col-sm-2">Status</label>
					              	<div class="col-sm-10">
					                	<select name="status" required>
					                  		<option value="">...</option>
					                  		<option value="1">new</option>
					                  		<option value="2">like new</option>
					                  		<option value="3">used</option>
					                  		<option value="4">old</option>
					                	</select>
					              	</div>
					            </div>
					            <!--categories-->
					            <div class="form-group form-group-lg">
					              	<label class="control-label col-sm-2">Category</label>
					              	<div class="col-sm-10">
					                	<select name="category" required>
					                  		<option value="">...</option>
								                <?php
								                //getAll($field, $table, $where = NULL, $and = NULL, $orderBy, $ordering = 'DESC')
								                    $cats = getAll('*', 'categories', '', '', 'ID');
							                    foreach ($cats as $cat) {
								                      	echo "<option value='" . $cat['ID'] . "'>" . $cat['name'] ."</option>";
								                    }
								                ?>
					                	</select>
				              		</div>
					            </div>
					            <!--tags-->
			                    <div class="form-group form-group-lg">
			                        <label class="control-label col-sm-2">Tags</label>
			                        <div class="col-sm-10">
			                            <input type="text" name="tags" class="form-control" placeholder="separete Tags with coma (,)">
			                        </div>
			                    </div>
					            <!--submit-->
					            <div class="form-group form-group-lg">
					              	<div class="col-sm-10 col-sm-offset-2">
					                	<input type="submit" value="Add New item" class="btn btn-primary btn-lg">
					              	</div>
					            </div>
					        </form>
				 		</div>
				 		<div class="col-md-4">
				 			<div class="thumbnail item-box live-preview">
				 				<span class="price ">
				 					$<span class="live-price">0</span>
				 				</span>
				 				<img class="img-responsive" src="images.png" />
				 				<div class="caption">
				 					<h3 class="live-title">Name</h3>
				 					<p class="live-desc">Description</p>
				 				</div>
				 			</div>
				 		</div>
				 	</div>
				 	<?php

				 		if (!empty($formErrors)) {
				 			//loop through errors
				 			foreach ($formErrors as $error) {
				 				
				 				echo "<div class='alert alert-danger'>" . $error . "</div>";

				 			}
				 		}

				 		if (isset($successMsg)) {

							echo "<div class='alert alert-success'>" . $successMsg . "</div>";
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