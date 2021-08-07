<?php 

function lang($phrase) {

	static $lang=array(

		'message' => 'اهلا وسهلا',
		'admin' => 'مدير الموقع'

	);

	return $lang[$phrase];

}