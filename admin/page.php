<?php
// categories [mange | delete | edit | add]

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// if page is main page 
if ($do == 'Manage') {

	echo "welcome you are in Manage page";
	echo "<a href='?do=Insert'>Add New Category +</a>";

} elseif ($do = 'Add') {

	echo "welcome you are in Add page";

} elseif ($do = 'Insert') {

	echo "welcome you are in Insert page";

} else {

	echo "Error There's No Page With This Name";

}