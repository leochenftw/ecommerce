<?php use SaltedHerring\Debugger as Debugger;

class MiniSubscribeForm extends Form {
	public function __construct($controller, $watch_id) {
		$fields = new FieldList();
        $member = Member::currentUser();
		$watch = Watch::get()->byID($watch_id);
        $product = $watch->Watching();

        $fields->push(LiteralField::create('Thumbnail', '<div class="product-thumbnail"><a href="' . $product->Link() . '"><img width="50" height="50" src="' . $product->getSquared()->FillMax(50, 50)->URL . '" alt="' . $product->Title . '" /></a></div>'));
        $fields->push(LiteralField::create('Title', '<div class="product-title"><a href="' . $product->Link() . '">' . $product->Title . '</a></div>'));
        $fields->push(CheckboxField::create('WatchInventory', '有货通知我', $watch->WatchInventory));
        $fields->push(CheckboxField::create('WatchPromo', '有抢购通知我', $watch->WatchPromo));
		$fields->push(HiddenField::create('WatchID', 'WatchID', $watch_id));

		$actions = new FieldList(
			$btnSubmit = FormAction::create('doSubscribe', '提交')->setUseButtonTag(true)
		);

		parent::__construct($controller, 'MiniSubscribeForm', $fields, $actions);
		$this->addExtraClass('mini-watch-form');
		$this->setAttribute('id', 'mini-watch-form-' . $watch_id);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'api/v/1/subscribe', $product->ID));
	}

	public function doSubscribe($data, $form) {
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {

		}

		return Controller::curr()->httpError(400, 'fuck off');

	}
}
