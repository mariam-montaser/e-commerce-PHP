<?php

// categories page 
// you can add category | delete | edit

ob_start();

session_start();

	$pageTitle = 'Categories';

  	if (isset($_SESSION['username'])) {
  		
  		include 'intial.php';
  		
  		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  		//start manage page 
  		if ($do == "Manage") {
  			//echo "welcome

        $sort = 'ASC';

        $sort_array = array('ASC', 'DESC');

        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

          $sort = $_GET['sort'];
        }

  			$stmt = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ordering $sort");

  			$stmt->execute();

  			$cats = $stmt->fetchAll();


        ?>

  			<h1 class="text-center">Manage Categories</h1>
  			<div class="container categories">
        <?php if (!empty($cats)) { ?>
  				<div class="panel panel-default">
  					<div class="panel-heading">
              <i class="fa fa-edit"></i>Manage Categories
              <div class="option pull-right">
                <i class="fa fa-sort"></i>Ordering: [
                <a href="?sort=ASC" class="<?php if ($sort == "ASC") { echo"active";}?>">ASC</a> |
                <a href="?sort=DESC" class="<?php if ($sort == "DESC") { echo"active";}?>">DESC</a> ]
                <i class="fa fa-eye"></i>veiw: [
                <span class="active" data-view="full">Full</span> |
                <span>Classic</span> ]
              </div>
            </div>
  					<div class="panel-body">
  						<?php
  							foreach ($cats as $cat) {
  								echo "<div class='cat'>"; 
                    echo "<div class='hidden-btn'>";
                      echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                      echo "<a href='Categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                    echo "</div>";
    								echo "<h3>" .$cat['name'] . "</h3>";
    								echo "<div class='full-mode'>";
                      echo "<p>";
                        if ($cat['descreption'] == '') {
                          echo "this category has no descreption";
                        } else {
                          echo $cat['descreption'];
                        }
                      echo "</p>";
                      if ($cat['visibility'] == 1) {echo "<span class='visibility'><i class='fa fa-eye'></i>Hidden</span>";}
                      if ($cat['allow_comment'] == 1) {echo "<span class='commenting'><i class='fa fa-close'></i>comments disable</span>";}
                      if ($cat['allow_adv'] == 1) {echo "<span class='adv'><i class='fa fa-close'></i>adv disable</span>";}
                      // get the childern categories

                      $childern = getAll('*', 'categories', "WHERE parent = {$cat['ID']}", '', 'ID', 'ASC');

                      if (!empty($childern)) {
                        echo "<h4 class='sub-head'>Sub Categories</h4>";
                        echo "<ul class='list-unstyled sub-cats'>";
                        foreach ($childern as $child) {

                          echo "<li class='child-link'>
                                  <a href='categories.php?do=Edit&catid=" . $child['ID'] . "'>" . $child['name'] . "</a>
                                  <a href='categories.php?do=Delete&catid=" . $child['ID'] . "' class='show-del confirm'>Delete</a>
                                </li>"; 
                        }
                        echo "</ul>";

                      }
                    echo "</div>";
  								echo "</div>";
  								
                  
                  echo "<hr>";
  							}
  						?>
  					</div>
  				</div>
          <?php } else {

              echo "<div class='alert alert-danger'>There's No Categories To Show</div>";
              
          }?>
          <a href="categories.php?do=Add" class="add-cat btn btn-primary"><i class="fa fa-plus"></i>Add New Category</a>
  			</div>

		  <?php
  		} elseif ($do == "Add") {//add page?>

  			<h1 class="text-center">Add New Category</h1>
  			<div class="container">
  				<form class="form-horizontal" action="?do=Insert" method="POST">
  					<!--name-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Name</label>
  						<div class="col-sm-10">
  							<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of new category">
  						</div>
  					</div>
  					<!--descreption-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Descreption</label>
  						<div class="col-sm-10">
  							<input type="text" name="descreption" class="form-control" placeholder="describe the category">
  						</div>
  					</div>
  					<!--ordering-->
  					<div class="form-group form-group-lg">
  						<label class="control-label col-sm-2">Ordering</label>
  						<div class="col-sm-10">
  							<input type="text" name="ordering" class="form-control" placeholder="Number to arrange the categories">
  						</div>
  					</div>
            <!--parent-->
            <div class="form-group form-group-lg">
              <label class="control-label col-sm-2">Parent Category</label>
              <div class="col-sm-10">
                <select name="parent">
                  <option value="0">None</option>
                  <?php
                    $allCats = getAll("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
                    foreach ($allCats as $cat) {

                      echo "<option value='" . $cat['ID'] . "'>" . $cat['name'] . "</option>";
                    }
                  ?>
                </select>
              </div>
            </div>
  					<!--visibility-->
  					<div class="form-group form-group-lg ">
  						<label class="control-label col-sm-2">Visible</label>
  						<div class="col-sm-10">
  							<div>
  								<input id="vis-yes" type="radio" name="visibility" value="0" checked >
  								<label for="vis-yes">Yes</label>
  							</div>
  							<div>
  								<input id="vis-no" type="radio" name="visibility" value="1" >
  								<label for="vis-no">No</label>
  							</div>
  						</div>
  					</div>
  					<!--comment-->
  					<div class="form-group form-group-lg ">
  						<label class="control-label col-sm-2">Comments</label>
  						<div class="col-sm-10">
  							<div>
  								<input id="com-yes" type="radio" name="comment" value="0" checked >
  								<label for="com-yes">Yes</label>
  							</div>
  							<div>
  								<input id="com-no" type="radio" name="comment" value="1" >
  								<label for="com-no">No</label>
  							</div>
  						</div>
  					</div>
  					<!--show adv-->
  					<div class="form-group form-group-lg ">
  						<label class="control-label col-sm-2">Advertising</label>
  						<div class="col-sm-10">
  							<div>
  								<input id="adv-yes" type="radio" name="adv" value="0" checked >
  								<label for="adv-yes">Yes</label>
  							</div>
  							<div>
  								<input id="adv-no" type="radio" name="adv" value="1" >
  								<label for="adv-no">No</label>
  							</div>
  						</div>
  					</div>
  					<!--submit-->
  					<div class="form-group form-group-lg">
  						<div class="col-sm-10 col-sm-offset-2">
  							<input type="submit" value="Add New category" class="btn btn-primary btn-lg">
  						</div>
  					</div>
  				</form>
  			</div>
  		<?php

  		} elseif ($do == "Insert") {//insert page

  			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  				echo '<h1 class="text-center">Insert New Category</h1>';
  				echo "<div class='container' >";

	  				//get vars from the form

	  				$name    = $_POST['name'];
	  				$desc    = $_POST['descreption'];
            $order   = $_POST['ordering'];
	  				$parent  = $_POST['parent'];
	  				$visible = $_POST['visibility'];
	  				$comment = $_POST['comment'];
	  				$adv     = $_POST['adv'];
	  			
	  				
	  				//insert the data in database
 
  					// check if category exist in database

  					$check = checkItem('name', 'categories', $name);

  					if ($check ==1) {

  						$theMessage = 'div class="alert alert-danger">This category is exist</div>';
  						redirectPage($theMessage, 'back');

  					} else {

  						// insert the data in database
	  					$stmt = $con->prepare("INSERT INTO categories(name, descreption, parent, ordering, visibility, allow_comment, allow_adv) VALUES(:NAME, :DES, :PARENT, :ORDER, :VISIBLE, :COMMENT, :ADV)");
		  				$stmt->execute(array(
		  					'NAME'     => $name,
		  					'DES'      => $desc,
                'PARENT'   => $parent,
		  					'ORDER'    => $order,
		  					'VISIBLE'  => $visible,
		  					'COMMENT'  => $comment,
		  					'ADV'      => $adv   
		  				));

		  				// success message
		  				$theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record inserted </div>';
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

        // check if GET request catid is numeric and the value of it
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        // select all data with this id
        $stmt = $con->prepare('SELECT * FROM categories WHERE ID=?');
        // execute the query
        $stmt->execute(array($catid));
        // fetch the data
        $cat = $stmt->fetch();
        // the row count
        $count = $stmt->rowCount();

        // if there's such id
        if ($count > 0) {
          //echo "good";?>
          <h1 class="text-center">Edit Category</h1>
          <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
              <!--userid-->
              <input type="hidden" name="catid" value="<?php echo $catid; ?>">
              <!--name-->
              <div class="form-group form-group-lg">
                <label class="control-label col-sm-2">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" required="required" placeholder="Name of new category" value="<?php echo $cat['name'] ?>" />
                </div>
              </div>
              <!--descreption-->
              <div class="form-group form-group-lg">
                <label class="control-label col-sm-2">Descreption</label>
                <div class="col-sm-10">
                  <input type="text" name="descreption" class="form-control" placeholder="describe the category" value="<?php echo $cat['descreption'] ?>" />
                </div>
              </div>
              <!--ordering-->
              <div class="form-group form-group-lg">
                <label class="control-label col-sm-2">Ordering</label>
                <div class="col-sm-10">
                  <input type="text" name="ordering" class="form-control" placeholder="Number to arrange the categories" value="<?php echo $cat['ordering'] ?>" />
                </div>
              </div>
              <!--parent-->
              <div class="form-group form-group-lg">
                <label class="control-label col-sm-2">Parent Category</label>
                <div class="col-sm-10">
                  <select name="parent">
                    <option value="0">None</option>
                    <?php
                      $allCats = getAll("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
                      foreach ($allCats as $oneCat) {

                        echo "<option value='" . $oneCat['ID'] . "'";
                        if ($cat['parent'] == $oneCat['ID']) {
                          echo "selected";
                        }
                        echo ">" . $oneCat['name'] . "</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <!--visibility-->
              <div class="form-group form-group-lg ">
                <label class="control-label col-sm-2">Visible</label>
                <div class="col-sm-10">
                  <div>
                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['visibility'] == 0) { echo 'checked';} ?> />
                    <label for="vis-yes">Yes</label>
                  </div>
                  <div>
                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['visibility'] == 1) { echo 'checked';} ?> />
                    <label for="vis-no">No</label>
                  </div>
                </div>
              </div>
              <!--comment-->
              <div class="form-group form-group-lg ">
                <label class="control-label col-sm-2">Comments</label>
                <div class="col-sm-10">
                  <div>
                    <input id="com-yes" type="radio" name="comment" value="0" <?php if ($cat['allow_comment'] == 0) { echo 'checked';} ?> />
                    <label for="com-yes">Yes</label>
                  </div>
                  <div>
                    <input id="com-no" type="radio" name="comment" value="1" <?php if ($cat['allow_comment'] == 1) { echo 'checked';} ?> />
                    <label for="com-no">No</label>
                  </div>
                </div>
              </div>
              <!--show adv-->
              <div class="form-group form-group-lg ">
                <label class="control-label col-sm-2">Advertising</label>
                <div class="col-sm-10">
                  <div>
                    <input id="adv-yes" type="radio" name="adv" value="0" <?php if ($cat['allow_adv'] == 0) { echo 'checked';} ?> />
                    <label for="adv-yes">Yes</label>
                  </div>
                  <div>
                    <input id="adv-no" type="radio" name="adv" value="1" <?php if ($cat['allow_adv'] == 1) { echo 'checked';} ?> />
                    <label for="adv-no">No</label>
                  </div>
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

        echo '<h1 class="text-center">Update Category</h1>';
        echo "<div class='container' >";
          
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              //get vars from the form

              $id = $_POST['catid'];
              $name = $_POST['name'];
              $desc = $_POST['descreption'];
              $order = $_POST['ordering'];
              $parent = $_POST['parent'];
              $visible = $_POST['visibility'];
              $comment = $_POST['comment'];
              $adv = $_POST['adv'];

              //update the data

              // if there's no errors

              if (!empty($name)) {

                $stmt = $con->prepare('UPDATE categories SET name = ?, descreption = ?, ordering = ?, parent = ?, visibility = ?, allow_comment = ?, allow_adv = ? WHERE id = ?');
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $adv, $id));

                $theMessage = '<div class="alert alert-success ">' . $stmt->rowCount(). 'record updated. </div>';
                redirectPage($theMessage, 'back');
              }

              
     
            } else {

              $theMessage = "<div class='alert alert-danger'>you not allowed to browse this page directly</div>";
              redirectPage($theMessage);
            }

        echo "</div>";

  		} elseif ($do == "Delete") {//delete member page

  			echo '<h1 class="text-center">Delete Category</h1>';
        echo "<div class='container' >";

        // check if GET request catid is numeric and the value of it
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        // select all data with this id
        //$stmt = $con->prepare('SELECT * FROM name WHERE userid=? LIMIT 1');
        // execute the query
        //$stmt->execute(array($userid));
        // the row count
        $check = checkItem('ID', 'Categories', $catid);

        // if there's such id
        if ($check > 0) {  

          $stmt = $con->prepare('DELETE FROM categories WHERE ID = :ID');
          $stmt->bindParam('ID', $catid);
          $stmt->execute();
          $theMessage = "<div class='alert alert-success' >" . $check . "record deleted. </div>";
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