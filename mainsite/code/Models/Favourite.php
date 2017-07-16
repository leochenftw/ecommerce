<?php

class Favourite extends DataObject {
	private static $db = array(
		'Session'		=>	'Varchar(255)'
	);

	private static $has_one = array(
		'Customer'		=>	'Member',
		'Product'		=>	'ProductPage'
	);

	private static $summary_fields = array(
		'Title',
		'theProduct'
	);

	private static $field_labels = array(
		'theProduct'		=>	'Product'
	);

	public function theProduct() {
		return $this->Product()->Title;
	}

	public function Title() {
		return $this->getTitle();
	}

	public function getTitle() {
		return !empty($this->CustomerID) ?
			($this->Customer()->isEnglish() ? ($this->Customer()->FirstName . ' ' . $this->Customer()->Surname) : ($this->Customer()->Surname.$this->Customer()->FirstName)) :
			$this->Session;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!$this->exists()) {
			if ($memberID = Member::currentUserID()) {
				$this->CustomerID = $memberID;
			} else {
				$this->Session = session_id();
			}
		}
	}
}
