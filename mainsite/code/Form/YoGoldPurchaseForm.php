<?php use SaltedHerring\Debugger as Debugger;

class YoGoldPurchaseForm extends Form {
	public function __construct($controller) {
		$member = Member::currentUser();
		$fields = new FieldList();
		$fields->push(LiteralField::create('CurrentYogold', '<div class="current-balance">当前余额 <span>' . $member->Credit . '</span></div>'));
		$fields->push($email = EmailField::create('YoGoldAmount', '请输入充值金额')->setAttribute('placeholder', '100.00'));
		
		$actions = new FieldList(
			$btnSubmit = FormAction::create('doPurchase','充值')
		);
			
		parent::__construct($controller, 'YoGoldPurchaseForm', $fields, $actions);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'member', "YoGoldPurchaseForm"));
	}
	
	public function doPurchase($data, $form) {
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
			
			return Controller::curr()->redirectBack();
		}
		
		return Controller::curr()->httpError(400, 'fuck off');
		
	}
}