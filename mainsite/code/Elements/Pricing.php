<?php

class Pricing extends DataObject {
	protected static $db = array(
		'Cost'				=>	'Decimal',
		'Price'				=>	'Decimal'
	);

	protected static $default_sort = array('ID' => 'DESC');

	protected static $summary_fields = array (
		'Created',
		'Cost',
		'Price'
	);

	protected static $has_one = array(
		'ProductPricing'	=>	'ProductPage',
		'VariantPricing'	=>	'Variant'
	);

	protected static $extensions = array(
		'ApisedExt'
	);

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!empty($this->Cost) && empty($this->Price)) {
			$this->Price = $this->Cost * 1.5;
		}
	}

}
