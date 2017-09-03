<?php

class Category extends DataObject {
	protected static $db = array(
		'SortOrder'		=>	'Int',
        'ShowTitle'     =>  'Boolean',
        'ShowButton'    =>  'Boolean',
        'Title'			=>	'Varchar(48)',
		'Subtitle'		=>	'Varchar(256)',
		'Intro'			=>	'Text'
    );

    /**
     * Belongs_to relationship
     * @var array
     */
    private static $defaults = array(
        'ShowTitle'     =>  true,
        'ShowButton'    =>  true
    );

	protected static $default_sort = array(
		'SortOrder'		=>	'ASC',
		'ID'			=>	'DESC'
	);

	protected static $extensions = array(
		'Translatable'
	);

	protected static $has_one = array(
		'HorizPoster'	=>	'Image',
		'SquarePoster'	=>	'Image',
		'VertiPoster'	=>	'Image'
	);

    protected static $has_many = array(
		'Product'		=>	'ProductPage'
    );

	public function forTemplate() {
		return $this->renderWith('CategoryTile');
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SortOrder');

		return $fields;
	}
}
