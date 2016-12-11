<?php

class Tag extends DataObject {
	private static $db = array(
        'Title'		=>	'Varchar(200)',
    );

    private static $belongs_many_many = array(
		'Products'	=>	'ProductPage',
		'Blogs'		=>	'SoftAdsPage'
    );
	
	public function forTemplate() {
		return $this->renderWith('TagTile');
	}
}