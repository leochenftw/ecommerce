<?php

class Shipment extends DataObject {
	protected static $db = array(
		'TrackingNumber'	=>	'Varchar(128)',
	);
	
	protected static $has_one = array(
		'Order'				=>	'Order',
		'FreightCompany'	=>	'FreightOption'
	);
	
	protected static $extensions = array(
		'ApisedExt'
	);
	
	public function Title() {
		return $this->TrackingNumber;
	}
	
	public function getTitle() {
		return $this->Title();
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
	}
}