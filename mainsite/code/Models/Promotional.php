<?php use SaltedHerring\Debugger as Debugger;

class Promotional extends DataObject {
	protected static $db = array(
		'SortOrder'	=>	'Int',
		'Title'		=>	'Varchar(128)',
		'Content'	=>	'Text'
	);
	
	protected static $default_sort = array(
		'SortOrder'	=>	'ASC',
		'ID'		=>	'DESC'		
	);
	
	protected static $has_one = array(
		'Image'		=>	'Image',
		'LinkTo'	=>	'Link'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SortOrder');
		if (empty($this->ID)) {
			$fields->addFieldToTab('Root.Main', DropdownField::create('FastLink', '使用现有团购信息', Groupon::get()->map('ID', 'Title'))->setEmptyString('- 选一个 -'), 'Title');
			$fields->addFieldToTab('Root.Main', TextField::create('CallToAction', '按钮文字'), 'Title');
		}
		$fields->addFieldToTab('Root.Main', LinkField::create('LinkToID', 'Link to'));
		return $fields;
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!empty($this->record['FastLink'])) {
			$groupon = Groupon::get()->byID($this->record['FastLink']);
			$this->Title = $groupon->Product()->Title;
			$this->Content = $groupon->Content;
			$this->ImageID = $groupon->Product()->PosterID;
			$link = new Link();
			if (!empty($this->record['CallToAction'])) {
				$link->Title = $this->record['CallToAction'];
			}
			$link->Type = 'SiteTree';
			$link->SiteTreeID = $groupon->ProductID;
			$link->write();
			$this->LinkToID = $link->ID;
		}
		$this->Content = strip_tags($this->Content);
	}
	
	public function forTemplate() {
		return $this->renderWith('Promotional');
	}
	
}
