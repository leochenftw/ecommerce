<?php

class FreightOption extends DataObject {
	protected static $db = array(
		'Title'		=>	'Varchar(48)',
		'Cost'		=>	'Currency',
		'Price'		=>	'Currency'
	);
	
	protected static $has_one = array(
		'Logo'		=>	'Image'
	);
	
	protected static $extensions = array(
		'ApisedExt'
	);
	
	/*public function getTitle() {
		return !empty($this->Alias) ? $this->Alias : $this->Title;
	}*/
	
	public function format() {
		return array(
					'id'	=>	$this->ID,
					'title'	=>	$this->Title,
					'costs'	=>	$this->Cost,
					'price'	=>	$this->Price
				);
	}
}