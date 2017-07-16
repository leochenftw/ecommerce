<?php use SaltedHerring\Grid as Grid;
use SaltedHerring\Debugger as Debugger;

class Variant extends DataObject {

	protected static $db = array(
		'SortOrder'			=>	'Int',
		'Title'				=>	'Varchar(254)'
	);

	protected static $has_one = array(
		'Product'			=>	'ProductPage'
	);

	protected static $has_many = array(
		'Photos'			=>	'Image'
	);

	protected static $extensions = array(
		'ApisedExt',
        'ProductAspectExtension'
	);

	protected static $summary_fields = array(
		'Title',
		'Price'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SortOrder');
		// $fields->removeByName('ProductID');
		$fields->removeByName('Photos');

        if (!$this->exists()) {
            if (!empty($this->ProductID)) {
                $measurement_field = $fields->fieldByName('Root.Measurements.Measurement');
                $measurement_field->setValue($this->Product()->Measurement);
            }
        }

		$fields->addFieldsToTab(
			'Root.Main',
			array(
				TextField::create('Title', '品种名称'),
			)
		);

		if (!empty($this->ID)) {
			$fields->addFieldToTab(
				'Root.Main',
				$grid = UploadField::create(
				    'Photos',
				    'Photos'
				)
			);

		}
		/*
		$config = $grid->getConfig();
		$config->addComponent(new GridFieldGalleryTheme('Photo'));
		*/

		return $fields;
	}

	public function format($map = null) {
		if (!empty($map)) {
			$data = array();
			foreach ($map as $key => $value) {
				if ($this->hasField($value)) {
					$data[$key] = $this->$value;
				} else if (method_exists($this, $value)) {
					$data[$key] = $this->$value();
				}
			}

			return $data;
		}
		return array(
					'id' => $this->ID,
					'title' => $this->Title,
                    'chinese' => $this->Chinese,
					'metrics' => $this->Type,
					'price' => $this->Price
				);
	}
}
