<?php use SaltedHerring\Debugger as Debugger;

class PaymentForm extends Form {
			
	public function __construct($controller) {
		
		$fields = new FieldList();
		
		$fields->push(OptionsetField::create('PaymentMethod', '支付方式', PaymentMethod::get()->map('ID', 'Title')));
		
		$actions = new FieldList(
			$btnSubmit = FormAction::create('ProcessPayment','付款')->performReadonlyTransformation()
		);
		
		$required_fields = array(
			'PaymentMethod'
		);
		
		$required = new RequiredFields($required_fields);
			
		parent::__construct($controller, 'PaymentForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "PaymentHandler"));
	}
	
	public function ProcessPayment($data, $form) {
		if ($data['SecurityID'] == Session::get('SecurityID')) {
			$payment_method = PaymentMethod::get()->byID($data['PaymentMethod']);
			$credit = Member::currentUser() ? Member::currentUser()->Credit : 0;
			$order = Utils::getCurrentCart(Member::currentUser() ? Member::currentUserID() : session_id());
			$amount_due = $order->AmountDue;
			if ($payment_method->Title == '优Gold') {
				if ($credit < $amount_due) {
					$this->sessionMessage('优Gold余额不足', 'bad');
					return Controller::curr()->redirectBack();
				}
			} elseif ($payment_method->Title == '银行转账') {
				
			}
			
			$order->Progress = 2;
			$order->write();
			
			$transaction = new Transaction();
			$transaction->AmountReceived = $amount_due;
			$transaction->OrderID = $order->ID;
			$transaction->Reference = !empty($data['reference-number']) ? $data['reference-number'] : null;
			$transaction->PaymentMethodID = $data['PaymentMethod'];
			$transaction->write();
		}
	}
}