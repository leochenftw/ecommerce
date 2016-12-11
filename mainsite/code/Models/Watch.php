<?php

class Watch extends DataObject {
	protected static $db = array(
		'Email'				=>	'Varchar(256)',
		'WatchInventory'	=>	'Boolean',
		'WatchPromo'		=>	'Boolean'
	);

	protected static $summary_fields = array(
		'Title'
	);

	protected static $has_one = array(
		'Watcher'			=>	'Member',
		'Watching'			=>	'ProductPage'
	);

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!empty($this->WatcherID)) {
			$this->Email = $this->Watcher()->Email;
		}
	}

	public function Title() {
		return $this->Email;
	}

	public function getTitle() {
		return $this->Title();
	}
}
