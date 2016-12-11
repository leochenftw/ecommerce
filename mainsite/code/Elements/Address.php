<?php

class Address extends DataObject {
	protected static $db = array(
		'Title'			=>	'Text',
		'Surname'		=>	'Varchar(128)',
		'FirstName'		=>	'Varchar(128)',
		'Email'			=>	'Varchar(256)',
		'Phone'			=>	'Varchar(48)',
		'PostCode'		=>	'Varchar(16)',
		'isDefault'		=>	'Boolean'
	);
	
	protected static $has_one = array(
		'Member'	=>	'Member'
	);
	
	protected static $extensions = array(
		'ApisedExt'
	);
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if (empty($this->byPassBeforeWrite)) {
			$member = !empty($this->MemberID) ? $this->Member() : Member::currentUser();
			
			$default = $member->Address()->filter(array('isDefault' => true));
			
			if ($default->count() == 0) {
				$this->isDefault = true;
			} else {
				if ($this->isDefault) {
					$default = $default->exclude('ID', $this->ID);
					foreach ($default as $item) {
						$item->byPassBeforeWrite = true;
						$item->isDefault = false;
						$item->write();
					}
				}
			}
		}
	}
}