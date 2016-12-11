<?php use SaltedHerring\Debugger as Debugger;

class DualColModel extends DataObject {
	protected static $db = array(
		'SortOrder'	=>	'Int',
		'Title'		=>	'Varchar(16)',
		'Content'	=>	'HTMLText'
	);
	
	protected static $has_one = array(
		'Image'		=>	'Image',
		'inPage'	=>	'Page'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SortOrder');
		return $fields;
	}
}