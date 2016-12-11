<?php

class Identification extends DataObject {
	
	protected static $db = array(
		'Title'			=>	'Varchar(128)'
	);
	
	protected static $has_one = array(
		'BelongTo'		=>	'Customer'
	);
	
	protected static $has_many = array(
		'Photos'		=>	'Image'
	);
	
}