<?php

// mange members 
// you can add admin | delete | edit

ob_start();

session_start();

	$pageTitle = '';

  	if (isset($_SESSION['username'])) {
  		
  		include 'intial.php';
  		
  		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  		//start manage page 
  		if ($do == "Manage") {

  			
  		} elseif ($do == "Add") {//add page

  			
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