<?php

class ImageExt extends DataExtension {
	protected static $has_one = array(
		'ProductPhoto'	=>	'ProductPage',
		'ProductDesc'	=>	'ProductPage',
		'IDPhoto'		=>	'Identification',
		'VariantPhoto'	=>	'Variant',
		'SoftAds'		=>	'SoftAdsPage'
	);
}