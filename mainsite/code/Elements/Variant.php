<?php use SaltedHerring\Grid as Grid;
use SaltedHerring\Debugger as Debugger;

class Variant extends DataObject {

	protected static $db = array(
		'SortOrder'			=>	'Int',
		'Measurement'		=>	'Varchar(16)',
		'Title'				=>	'Varchar(254)',
		'Weight'			=>	'Decimal'
	);

	protected static $has_one = array(
		'Product'			=>	'ProductPage'
	);

	protected static $has_many = array(
		'Pricings'			=>	'Pricing.VariantPricing',
		'Photos'			=>	'Image'
	);

	protected static $extensions = array(
		'ApisedExt'
	);

	protected static $summary_fields = array(
		'Title',
		'Pricings.First.Price'
	);

	public function PricingData() {
		return $this->Pricings()->format(array(
			'pricing_id'	=>	'ID',
			'cost'			=>	'Cost',
			'price'			=>	'Price',
			'created'		=>	'Created'
		));
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('SortOrder');
		$fields->removeByName('ProductID');
		$fields->removeByName('Pricings');
		$fields->removeByName('Photos');
		$onflyfield = array(
			'Cost'  => array(
				'title' => 'Cost',
				'field' => 'TextField'
			),
			'Price'  => array(
				'title' => 'Price',
				'field' => 'TextField'
			)
		);

		$fields->addFieldsToTab(
			'Root.Main',
			array(
				TextField::create('Title', '品种名称'),
				TextField::create('Weight', '重量')->setDescription('KG'),
				DropdownField::create('Measurement', '单位', Config::inst()->get('ProductPage', 'Measurements'))->setEmptyString('- select one -')
			)
		);

		if (!empty($this->ID)) {
			$fields->addFieldsToTab(
				'Root.Main',
				array(
					Grid::makeEditable('Pricings', 'Pricings', $this->Pricings()->sort('ID', 'DESC'),false, $onflyfield),
					$grid = UploadField::create(
					    'Photos',
					    'Photos'
					)
				)
			);

		}
		/*
		$config = $grid->getConfig();
		$config->addComponent(new GridFieldGalleryTheme('Photo'));
		*/

		return $fields;
	}

	public function getCost() {
		$costs = $this->Pricings()->sort('ID', 'DESC');
		if ($costs->count() > 0) {
			return $costs->first()->Cost;
		}
		return 0;
	}

	public function getPrice() {
		$prices = $this->Pricings()->sort('ID', 'DESC');
		if ($prices->count() > 0) {
			return $prices->first()->Price;
		}
		return 0;
	}

	public function onBeforeDelete() {

		$pricings = $this->Pricings()->sort('ID', 'DESC');
		foreach ($pricings as $pricing) {
			$pricing->delete();
		}
		parent::onBeforeDelete();
	}

	public function format($map = null) {
		if (!empty($map)) {
			$data = array();
			foreach ($map as $key => $value) {
				// Debugger::inspect($this->owner->ClassName, false);
				// Debugger::inspect($value, false);
				// Debugger::inspect(method_exists($this->owner, $value) ? 'yes' : 'no', false);
				if ($this->owner->hasField($value)) {
					$data[$key] = $this->owner->$value;
				} else if (method_exists($this->owner, $value)) {
					$data[$key] = $this->owner->$value();
				}
			}

			return $data;
		}
		return array(
					'id' => $this->ID,
					'title' => $this->Title,
					'metrics' => $this->Type,
					'cost' => $this->Pricings()->first()->Cost,
					'price' => $this->Pricings()->first()->Price
				);
	}
}
