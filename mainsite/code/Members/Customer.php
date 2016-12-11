<?php use SaltedHerring\Debugger as Debugger;

class Customer extends Member {
	
	protected static $db = array(
		'OldEmail'				=>	'Varchar(255)',
		'NewEmail'				=>	'Varchar(255)',
		'Credit'				=>	'Decimal',
		'WeChat'				=>	'Varchar(255)',
		'QQ'					=>	'Varchar(255)',
		'ValidationKey'  	 	=>	'Varchar(40)'
	);
	
	public function populateDefaults() {
		$this->owner->ValidationKey = sha1(mt_rand() . mt_rand());
	}
	
	protected static $has_many = array(
		'Orders'		=>	'Order'
	);
	
	public function onAfterWrite() {
		parent::onAfterWrite();
		
		if (!empty($this->ValidationKey)) {
			$email = new ConfirmationEmail($this);
			$email->send();
		}
	}
}