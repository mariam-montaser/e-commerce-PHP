<?php 

function lang($phrase) {

	static $lang = array(
		//navbar
		'HOMEPAGE' 	 => 'Home',
		'CATEGORIES' => 'Categories',
		'ADMIN NAME' => 'Mariam',
		'EDIT'       => 'Edit Profile',
		'SITTING'    => 'Sitting',
		'LOGOUT'     => 'Logout',
		'ITEMS'      => 'Items',
		'MEMBERS'    => 'Members',
		'STATISTICS' => 'Statistics',
		'COMMENTS'   => 'Comments',
		'LOGS'       => 'Logs'
		//

	);

	return $lang[$phrase];

}