<?php

//  items page 
// you can add item | delete | edit

ob_start();

session_start();

	$pageTitle = 'Items';

  	if (isset($_SESSION['username'])) {
  		
  		include 'intial.php';
  		
  		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  		//start manage page 
  		if ($do == "Manage") {

  			//echo "welcome to items page";

        $stmt = $con->prepare("SELECT items.*, categories.name as cat_name, name.username FROM items INNER JOIN categories ON items.cat_ID = categories.ID INNER JOIN name ON items.member_ID = name.userID ORDER BY itemID DESC");
        $stmt->execute();

        // assign to var
        $items = $stmt->fetchAll();

          ?>

          <h1 class="text-center">Manage Items</h1>
          <div class="container">
            <?phpif (!empty($items)) { ?>
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

                    echo "<div class='alert alert-danger'>There\'s No Record To Show</div>";

            } ?>
            <a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>Add New Items</a>
          </div>

      <?php

  		} elseif ($do == "Add") {//add page?>

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
          
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Insert New Item</h1>';
            echo "<div class='container' >";

              //get vars from the form

              $name = $_POST['name'];
              $desc = $_POST['description'];
              $price = $_POST['price'];
              $country = $_POST['country'];
              $status = $_POST['status'];
              $member = $_POST['member'];
              $cat = $_POST['category'];

              //echo $id . $username . $email . $fullname;
            
              // validate the form
              $formErrors = array();

              if (empty($name)) {

                $formErrors[] = 'Name field can\'t be <strong>empty</strong>.';
              }

              if (empty($desc)) {

                $formErrors[] = 'Description field can\'t be <strong>empty</strong>.';
              }

              if (empty($price)) {

                $formErrors[] = 'Price field can\'t be <strong>empty</strong>. ';
              }

              if (empty($country)) {

                $formErrors[] = 'Country field can\'t be <strong>empty</strong>. ';
              }

              if ($status === 0) {

                $formErrors[] = 'You must choose the <strong>status</strong>. ';
              }

              if ($member === 0) {

                $formErrors[] = 'You must choose the <strong>member</strong>. ';
              }

              if ($cat === 0) {

                $formErrors[] = 'You must choose the <strong>category</strong>. ';
              }

              foreach ($formErrors as $error) {

                echo '<div class= "alert alert-danger" >' . $error . '</div> <br /> ';
                
              }

              //insert the data in database

              // if there's no errors

              if (empty($formErrors)) {

                // insert the data in database
                $stmt = $con->prepare("INSERT INTO items(name, description, price, country_made, status, add_date, cat_ID, member_ID) VALUES(:NAME, :DESCR, :PRICE, :COUNTRY, :STATUS, now(), :CAT, :MEMBER)");
                $stmt->execute(array(
                  'NAME'    => $name,
                  'DESCR'   => $desc, 
                  'PRICE'   => $price,
                  'COUNTRY' => $country,
                  'STATUS'  => $status,
                  'CAT'     => $cat,
                  'MEMBER'  => $member

                ));

                // success message
                $theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'Item inserted </div>';
                redirectPage($theMessage, 'back' );

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
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        // select all data with this id
        $stmt = $con->prepare('SELECT * FROM items WHERE itemID=? ');
        // execute the query
        $stmt->execute(array($itemid));
        // fetch the data
        $item = $stmt->fetch();
        // the row count
        $count = $stmt->rowCount();

        // if there's such id
        if ($count > 0) {
          //echo "good";?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
              <form class="form-horizontal" action="?do=Update" method="POST">
                <!--item id-->
                <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                <!--name-->
                <div class="form-group form-group-lg">
                  <label class="control-label col-sm-2">Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="<?php echo $item['name']; ?>" required="required">
                  </div>
                </div>
                <!--description-->
                <div class="form-group form-group-lg">
                  <label class="control-label col-sm-2">Description</label>
                  <div class="col-sm-10">
                    <input type="text" name="description" value="<?php echo $item['description']; ?>" class="form-control" placeholder="">
                  </div>
                </div>
                <!--price-->
                <div class="form-group form-group-lg">
                  <label class="control-label col-sm-2">Price</label>
                  <div class="col-sm-10">
                    <input type="text" name="price" value="<?php echo $item['price']; ?>" class="form-control" required="required">
                  </div>
                </div>
                <!--country-->
                <div class="form-group form-group-lg ">
                  <label class="control-label col-sm-2">Country</label>
                  <div class="col-sm-10">
                    <input type="text" name="country" value="<?php echo $item['country_made']; ?>" class="form-control" required="required">
                  </div>
                </div>
                <!--status-->
                <div class="form-group form-group-lg">
                  <label class="control-label col-sm-2">Status</label>
                  <div class="col-sm-10">
                    <select name="status">
                      <option value="1" <?php if ($item['status'] == 1) { echo 'selected'; } ?>>new</option>
                      <option value="2" <?php if ($item['status'] == 2) { echo 'selected'; } ?>>like new</option>
                      <option value="3" <?php if ($item['status'] == 3) { echo 'selected'; } ?>>used</option>
                      <option value="4" <?php if ($item['status'] == 4) { echo 'selected'; } ?>>old</option>
                    </select>
                  </div>
                </div>
                <!--members-->
                <div class="form-group form-group-lg">
                  <label class="control-label col-sm-2">Member</label>
                  <div class="col-sm-10">
                    <select name="member">
                      <?php

                        $stmt = $con->prepare("SELECT * FROM name");

                        $stmt->execute();

                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                          echo "<option value='" . $user['userID'] . "'"; 
                          if ($user['userID'] == $item['member_ID']) { echo 'selected'; }
                          echo ">" . $user['username'] ."</option>";
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
                      <?php

                        $stmt = $con->prepare("SELECT * FROM categories");

                        $stmt->execute();

                        $cats = $stmt->fetchAll();

                        foreach ($cats as $cat) {
                          echo "<option value='" . $cat['ID'] . "'";
                          if ($cat['ID'] == $item['cat_ID']) { echo 'selected'; }
                          echo ">" . $cat['name'] ."</option>";
                        }
                       ?>
                    </select>
                  </div>
                </div>
                <!--submit-->
                <div class="form-group form-group-lg">
                  <div class="col-sm-10 col-sm-offset-2">
                    <input type="submit" value="Save" class="btn btn-primary btn-lg">
                  </div>
                </div>
              </form>
              <!--comments on this item-->
              <?php
              $stmt = $con->prepare("SELECT
                                        comments.*, name.username
                                      FROM comments
                                      INNER JOIN name
                                      ON name.userID = comments.user_id
                                      WHERE item_id = ?");
              $stmt->execute(array($itemid));

              // assign to var
              $rows = $stmt->fetchAll();

              if (!empty($rows)) {

                ?>

                <h1 class="text-center">Manage [<?php echo $item['name']; ?>] Comments</h1>
                <div class="table-responsive">
                  <table class="table main-table table-bordered text-center">
                    <tr>
                      <td>Comment</td>
                      <td>User Name</td>
                      <td>Added Date</td>
                      <td>Control</td>
                    </tr>

                    <?php 

                    foreach ($rows as $row) {
                      echo '<tr>';
                        echo '<td>'. $row['comment'] . '</td>';
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
             <?php } ?>
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

        echo '<h1 class="text-center">Update Item</h1>';
        echo "<div class='container' >";
          
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              //get vars from the form

              $id = $_POST['itemid'];
              $name = $_POST['name'];
              $desc = $_POST['description'];
              $price = $_POST['price'];
              $country = $_POST['country'];
              $status = $_POST['status'];
              $member = $_POST['member'];
              $cat = $_POST['category'];

              //echo $id . $username . $email . $fullname;
              $formErrors = array();

              if (empty($name)) {

                $formErrors[] = 'Name field can\'t be <strong>empty</strong>.';
              }

              if (empty($desc)) {

                $formErrors[] = 'Description field can\'t be <strong>empty</strong>.';
              }

              if (empty($price)) {

                $formErrors[] = 'Price field can\'t be <strong>empty</strong>. ';
              }

              if (empty($country)) {

                $formErrors[] = 'Country field can\'t be <strong>empty</strong>. ';
              }

              if ($status === 0) {

                $formErrors[] = 'You must choose the <strong>status</strong>. ';
              }

              if ($member === 0) {

                $formErrors[] = 'You must choose the <strong>member</strong>. ';
              }

              if ($cat === 0) {

                $formErrors[] = 'You must choose the <strong>category</strong>. ';
              }

              foreach ($formErrors as $error) {

                echo '<div class= "alert alert-danger" >' . $error . '</div> <br /> ';
              }

              //update the data

              // if there's no errors

              if (empty($formErrors)) {

                $stmt = $con->prepare("UPDATE items SET name = ?, description = ?, price = ?, country_made = ?, status = ?, member_ID = ?, cat_ID = ? WHERE itemID = ?");
                $stmt->execute(array($name, $desc, $price, $country, $status, $member, $cat, $id));

                $theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record updated. </div>';
                redirectPage($theMessage, 'back');
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
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    
        $check = checkItem('itemID', 'items', $itemid);

        // if there's such id
        if ($check > 0) {  

          $stmt = $con->prepare('DELETE FROM items WHERE itemID = :ID');
          $stmt->bindParam('ID', $itemid);
          $stmt->execute();
          $theMessage = "<div class='alert alert-success' >" . $check . "record deleted. </div>";
          redirectPage($theMessage, 'back');

        } else {

          $theMessage = "<div class='alert alert-danger'>There's no such ID</div>";
          redirectPage($theMessage);
        }

        echo "</div>";
  		
  		} elseif ($do == "Approve") {//Activate page

        echo '<h1 class="text-center">Approve Item</h1>';
        echo "<div class='container' >";

        // check if GET request itemid is numeric and the value of it
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        
        // the row count
        $check = checkItem('itemID', 'items', $itemid);

        // if there's such id
        if ($check > 0) {  

          $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE itemID = ?");
          //$stmt->bindParam('ID', $userid);
          $stmt->execute(array($itemid));
          $theMessage = "<div class='alert alert-success' >" . $check . "record Approved. </div>";
          redirectPage($theMessage, 'back');

        } else {

          $theMessage = "<div class='alert alert-danger'>There's no such ID</div>";
          redirectPage($theMessage);
        }

        echo "</div>";
  			
		  }


  		include $tpl . 'footer.php';

  	// } else {

  	// 	header('location:index.php');

  	// 	exit();
  	// }

ob_end_flush();
?>