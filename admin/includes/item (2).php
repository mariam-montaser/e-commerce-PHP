<?php
// mange Items 
// you can add item | delete | edit

ob_start();

session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['username'])) {
		
		include 'intial.php';
		
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		//start manage page 
		if ($do == "Manage") {

            $stmt = $con->prepare("SELECT items.*, categories.name as cat_name, name.username 
                                    FROM items 
                                    INNER JOIN categories 
                                    ON items.cat_ID = categories.ID 
                                    INNER JOIN name ON items.member_ID = name.userID 
                                    ORDER BY itemID DESC");
            $stmt->execute();

            // assign to var
            $items = $stmt->fetchAll();

            ?>

            <h1 class="text-center">Manage Items</h1>
            <div class="container">
            <?php if (!empty($items)) { ?>
                <div class="table-responsive">
                    <table class="table main-table table-bordered text-center">
                        <tr>
                          <td>#ID</td>
                          <td>Name</td>
                          <td>Description</td>
                          <td>Price</td>
                          <td>Adding Date</td>
                          <td>Category</td>
                          <td>Username</td>
                          <td>Control</td>
                        </tr>

                        <?php 

                        foreach ($items as $item) {
                          echo '<tr>';
                            echo '<td>'. $item['itemID'] . '</td>';
                            echo '<td>'. $item['name'] . '</td>';
                            echo '<td>'. $item['description'] . '</td>';
                            echo '<td>'. $item['price'] . '</td>';
                            echo '<td>'. $item['add_date'] .'</td>';
                            echo '<td>'. $item['cat_name'] .'</td>';
                            echo '<td>'. $item['username'] .'</td>';
                            echo '<td>
                              <a href="items.php?do=Edit&itemid=' . $item['itemID'] . '" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a>
                              <a href="items.php?do=Delete&itemid=' . $item['itemID'] . '" class=" confirm  btn btn-danger"><i class="fa fa-close"></i>Delete</a>';
                              if ($item['approve'] == 0) {
                                echo '<a href="items.php?do=Approve&itemid=' . $item['itemID'] . '" class=" activate btn btn-info"><i class="fa fa-check"></i>Approve</a>';
                              }
                            echo '</td>';
                          echo '</tr>';
                        }

                        ?>
                
                    </table>
                </div>
                <?php } else {

                            echo "<div class='alert alert-danger'>There's No Items To Show</div>";

                } ?>
                <a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>Add New Items</a>
            </div>

        <?php
	    } elseif ($do == "Add") { //add page ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!--name-->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of new item">
                        </div>
                    </div>
                    <!--descreption-->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" class="form-control" required="required" placeholder="describe the item">
                        </div>
                    </div>
                    <!--price-->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Price</label>
                        <div class="col-sm-10">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price of the item">
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
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">new</option>
                                <option value="2">like new</option>
                                <option value="3">used</option>
                                <option value="4">old</option>
                            </select>
                        </div>
                    </div>
                    <!--members-->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Member</label>
                        <div class="col-sm-10">
                            <select name="member">
                                <option value="0">...</option>
                                  <?php

                                    $stmt = $con->prepare("SELECT * FROM name");

                                    $stmt->execute();

                                    $users = $stmt->fetchAll();

                                    foreach ($users as $user) {
                                      echo "<option value='" . $user['userID'] . "'>" . $user['username'] ."</option>";
                                    }
                                   ?>
                            </select>
                        </div>
                    </div>
                    <!--categories-->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Category</label>
                        <div class="col-sm-10">
                            <select name="category">
                                <option value="0">...</option>
                                  <?php

                                    $stmt = $con->prepare("SELECT * FROM categories");

                                    $stmt->execute();

                                    $cats = $stmt->fetchAll();

                                    foreach ($cats as $cat) {
                                      echo "<option value='" . $cat['ID'] . "'>" . $cat['name'] ."</option>";
                                    }
                                   ?>
                            </select>
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

		  <?php
        } elseif ($do == "Insert") {//insert page

			
		

		} elseif ($do == "Edit") {//edit page 

			
		} elseif ($do == "Update") {//update page

		} elseif ($do == "Delete") {//delete member page
			
		
		} elseif ($do == "Activate") {//Activate page

			
	   }


		include $tpl . 'footer.php';

	} else {

		//echo "you are not autherised";  

		header('location:index.php');

		exit();
	} 

ob_end_flush();
?>