<?php


// getAll function v1.0
// getAll function v2.0 general function
// get all from any DB table 
function getAll($field, $table, $where = NULL, $and = NULL, $orderBy, $ordering = 'DESC') {

	global $con;

	//$sql = $where == NULL ? '' : $where;

	$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderBy $ordering");

	$getAll->execute();

	$all = $getAll->fetchAll();

	return $all;

}


//gitTitle function v1.0
//title function with $pageTitle or with default title
function getTitle() {

	global $pageTitle;

	if (isset($pageTitle)) {

		echo $pageTitle;

	} else {

		echo 'default'; 

	}
}


// homeRedirect function v1.0
// redirectPage function v2.0
// home redirect function with parameters(the error message, the second before direct)
function redirectPage($theMessage, $url = null, $seconds = 3) {

	if ($url === null) {

		$url = 'index.php';
		$link = 'homepage';

	} else {

		// check if there's http referer

		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

			$url = $_SERVER['HTTP_REFERER'];

			$link = 'Previous Page';

		} else {

			$url = 'index.php';
			$link = 'Homepage';
		}
		
	}

	echo $theMessage ;
	echo "<div class='alert alert-info'>You Will Be Redirect To " . $link . " In " . $seconds . " Seconds. </div>";

	header("refresh:$seconds;url=$url");
	exit();
}  


//checkItem function v1.0
//check if item exist in database with parameters(item to select, the table to select from, the value of the item selected)
function checkItem($select, $from, $value) {

	global $con;

	$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
	$statement ->execute(array($value));

	$count = $statement->rowCount();

	return $count;

} 


// countItems function v1.0
// count the number of items with parameters(the item to count, the table where items)

function countItems($item, $table) {

	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

	$stmt2->execute();
 
	return $stmt2->fetchColumn();
}

// getLatest function v1.0
// get the latest items with parameters(the item to get, the table to get from, the number you want)
function getLatest($select, $table, $order, $limit = 5) {

	global $con;

	$getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

	$getstmt->execute();

	$rows = $getstmt->fetchAll();

	return $rows;

}

