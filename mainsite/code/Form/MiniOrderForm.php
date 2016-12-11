<?php use SaltedHerring\Debugger as Debugger;

class MiniOrderForm extends Form {
	
	public function __construct($product) {
		$fields = new FieldList();
		if ($product->Variants()->count() > 0) {
			$fields->push($variant = DropdownField::create('Variant', $product->Variants()->first()->Type, $product->Variants()->map('ID', 'Title')));
			$fields->push(LiteralField::create('PriceJS', '<script> var price_map = ' . json_encode($product->Variants()->map('ID', 'Price')->toArray()) . '</script>'));
			$variant->setTitle('种类');
		}
				
		$fields->push($quantity = TextField::create('Quantity')->setAttribute('value', 1));
		
		$quantity->setTitle('数量');
		
		$actions = new FieldList(
			$btnSubmit = FormAction::create('addToCart','买买买'),
			$btnShortlist = FormAction::create('addToFavourite', '收藏')->addExtraClass('icon-fav')->setUseButtonTag(true)
		);
		
		if ($product->isFav()) {
			$btnShortlist->addExtraClass('is-fav');
		}
		
		$btnSubmit->addExtraClass('button');
		
		$required_fields = array(
			'Quantity'
		);
		
		if (!empty($variant)) {
			$required_fields[] = $variant;
		}
		
		$required = new RequiredFields($required_fields);
					
		parent::__construct(Controller::curr(), 'MiniOrderForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $product->Link(), "ProductOrderForm"));
	}
}