<?php use SaltedHerring\Debugger as Debugger;

class CartForm extends Form {
			
	public function __construct($controller, $order_id) {
		$member = Member::currentUser();
		$address = null;
		if ($member && !empty($member->Address()->count())) {
			$address = $member->Address();
		}
		$firstname = ($address && $address->count() > 0) ? $address->first()->FirstName : null;
		$lastname = ($address && $address->count() > 0) ? $address->first()->Surname : null;
		$email = ($address && $address->count() > 0) ? $address->first()->Email : null;
		$phone = ($address && $address->count() > 0) ? $address->first()->Phone : null;
		
		$fields = new FieldList();
		
		$order = Order::get()->byID($order_id);
		
		if ($address && !empty($address->count())) {
			/*$address = $address->Column('Title');
			$address[] = '新收货地址...';*/
			$fields->push(DropdownField::create('DeliveryAddress', '收货地址', $address->map('ID', 'Title'), $address->first()->ID)->setEmptyString('新收货地址...'));
		}
		$fields->push($new_addr = TextField::create('NewDeliveryAddress', '新收货地址'));
		
		if (!$member) {
			$new_addr->setAttribute('aria-required', true)->setAttribute('required','required');
		}
		
		/*$fields->push(TextField::create('BillingAddress', '发票地址'));
		$fields->push(CheckboxField::create('SameAsDelivery', 'same as delivery'));*/
		$fields->push(TextField::create('Surname', '姓', $lastname));
		$fields->push(TextField::create('FirstName', '名', $firstname));
		$fields->push(EmailField::create('Email', '电子邮箱', $email));
		if (!$member) {
			$fields->push(CheckboxField::create('AlsoSignup', '顺便注册会员'));
		}
		
		$freight_cost = FreightOption::get()->first()->Price * $order->getWeight();
		$order_total = $order->getSum(false);
		
		$selected_freight = !empty($order->UsingFreightID) ? $order->UsingFreightID : FreightOption::get()->first()->ID;
		$fields->push(TextField::create('Phone', '联系电话/手机', $phone)->setAttribute('placeholder', '格式: 04 xxx xxxx 或者 021 xxx xxxxx'));
		$fields->push(ReadonlyField::create('Weight', '重量', $order->getWeight() . ' KG'));
		$fields->push(OptionsetField::create('Freight', '选择快递公司', FreightOption::get()->map('ID','Title'), $selected_freight));
		$fields->push(ReadonlyField::create('FreightCost', '运费', '$' . number_format($freight_cost, 2, '.', ',')));
		$fields->push(ReadonlyField::create('Total', '总计', '$' . number_format(($freight_cost + $order_total), 2, '.', ',')));
		$actions = new FieldList(
			$btnSubmit = FormAction::create('doCheckout','结账')
		);
		
		$required_fields = array(
			'Surname',
			'FirstName',
			'Email',
			'Freight'
		);
		
		$required = new RequiredFields($required_fields);
			
		parent::__construct($controller, 'CartForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "checkout"));
	}
	
	/*public function validate() {
		
		$result = parent::validate();
		$data = !empty($_SESSION['FormInfo']['CartForm_CartForm']['data']) ? $_SESSION['FormInfo']['CartForm_CartForm']['data'] : null;
		if ($data) {
			$order = Utils::getCurrentCart(Member::currentUser() ? Member::currentUserID() : session_id());
			$freight_cost = FreightOption::get()->first()->Price * $order->getWeight();
			$order_total = $order->getSum(false);
			$_SESSION['FormInfo']['CartForm_CartForm']['data']['Weight'] = $order->getWeight() . ' KG';
			$_SESSION['FormInfo']['CartForm_CartForm']['data']['FreightCost'] = '$' . number_format($freight_cost, 2, '.', ',');
			$_SESSION['FormInfo']['CartForm_CartForm']['data']['Total'] = '$' . number_format(($freight_cost + $order_total), 2, '.', ',');
			Session::save();
		}
		
		return $result;
	}*/
	
	public function doCheckout($data, $form) {
		Session::clear('payment_token');
		Session::save();
		if ($data['SecurityID'] == Session::get('SecurityID')) {
			$address_id = empty($data['DeliveryAddress']) ? null : $data['DeliveryAddress'];
			$new_address = empty($data['NewDeliveryAddress']) ? null : $data['NewDeliveryAddress'];
			$surname = empty($data['Surname']) ? null : $data['Surname'];
			$firstname = empty($data['FirstName']) ? null : $data['FirstName'];
			$email = empty($data['Email']) ? null : $data['Email'];
			$phone = empty($data['Phone']) ? null : $data['Phone'];
			
			$perform_signup = empty($data['AlsoSignup']) ? null : $data['AlsoSignup'];
			$freight_id = empty($data['Freight']) ? null : $data['Freight'];
			
			if (empty($address_id) && !empty($new_address)) {
				
				if ($member = Member::currentUser()) {
					$order = Utils::getCurrentCart($member->ID);
					$newAddress = new Address();
					$form->saveInto($newAddress);
					$newAddress->Title = $new_address;
					$newAddress->MemberID = $member->ID;
					$newAddress->write();
					
				} else {
					$order = Utils::getCurrentCart(session_id());
					if ($this->isExistingMember($email)) {
						$form->addErrorMessage('Email', '您已是会员. 请<a href="/signin?session=' . session_id() . '">登录</a>', "bad", false); 
						//would be nice if can transfer cart content
						return Controller::curr()->redirectBack();
					}
					
					if (!empty($perform_signup)) {
						//Debugger::inspect('creating new account and then new address');
						$customer = new Customer();
						$form->saveInto($customer);
						$customer->write();
						$customer->Orders()->add($order);
						
						$newAddress = new Address();
						$form->saveInto($newAddress);
						$newAddress->Title = $new_address;
						$newAddress->MemberID = $customer->ID;
						$newAddress->write();
						
					} else {
						//Debugger::inspect('one-off');
					}
				}
			} else {
				//Debugger::inspect('using existing address');
				$order = Utils::getCurrentCart(Member::currentUserID());
				$existing_address = Address::get()->byID($address_id);
				$existing_address->Surname = $surname;
				$existing_address->FirstName = $firstname;
				$existing_address->Email = $email;
				$existing_address->Phone = $phone;
				$existing_address->write();				
			}
			
			$order->Surname = $surname;
			$order->FirstName = $firstname;
			$order->Email = $email;
			$order->Phone = $phone;
			$order->Address = !empty($newAddress) ? $newAddress : Address::get()->byID($address_id)->Title;
			$order->UsingFreightID = $freight_id;
			$order->Progress = 1;
			$order->write();
			Session::set('payment_token', session_id());
			Session::save();
			return Controller::curr()->redirect('/cart/payment');
		}
		$this->sessionMessage('您交钱的姿势不对 -- 再来一次', 'bad');
		return Controller::curr()->redirectBack();
	}
	
	private function isExistingMember($email) {
		$test_member = Member::get()->filter(array('Email' => $email));
		return $test_member->count() > 0;
	}
}