<?php use SaltedHerring\Debugger as Debugger;

class SignupForm extends Form {
			
	public function __construct($controller) {
		
		$fields = new FieldList();
		$fields->push($email = EmailField::create('Email', '电子邮件'));
		$fields->push($pass = ConfirmedPasswordField::create('Password', '密码'));
		/*$fields->push($last = TextField::create('Surname', '尊姓'));
		$fields->push($first = TextField::create('FirstName', '大名'));
		$fields->push($wechat = TextField::create('WeChat', 'WeChat'));
		$fields->push($qq = TextField::create('QQ', 'QQ'));*/
		$fields->push($tnc = CheckboxField::create('AgreeToTnC', '我已阅读并接受<a target="_blank" href="/terms-and-conditions">免责协议</a>和<a target="_blank" href="/privacy-policy">隐私条款</a>'));
		
		$fields->push(CheckboxField::create('Subscribe', '加入订阅计划, 第一时间收到风')->setValue(true));
		$actions = new FieldList(
			$btnSubmit = FormAction::create('doSignup','注册')
		);
		
		$required_fields = array(
			'Email',
			'Password',
			'AgreeToTnC'
			//'Surname',
			//'FirstName'
		);
		
		$required = new RequiredFields($required_fields);
			
		parent::__construct($controller, 'SignupForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "SignupForm"));
	}
	
	public function doSignup($data, $form) {
		
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
			
			if (!SaltedHerring\Utilities::valid_email($data['Email'])) {
				$form->addErrorMessage('Email', '"' . $data['Email'] . '"不是有效电子邮件地址', "bad");
				return Controller::curr()->redirectBack();
			}
			
			$member_exists = Member::get()->filter(array('Email' => $data['Email']));
			if (empty($member_exists->count())) {
				$check = Utils::PassCheck($data['Email'], $data['Password']['_Password']);
				
				if ($check['status']) {
					$customer = new Customer();
					$form->saveInto($customer);
					$customer->write();
					$this->sessionMessage('谢谢老板！我们已经发了一封激活邮件到您刚才注册的邮箱. 请点击邮件里的链接对您的账号进行激活.', 'good');
				} else {
					$messages = $check['messages'];
					$refined_message = '';
					foreach ($messages as $message) {
						$refined_message .= $message . "; ";
					}
					
					$this->sessionMessage(rtrim($refined_message, '; '), 'bad');
				}
			} else {
				$form->addErrorMessage('Email', '"' . $data['Email'] . '" 已被注册. <a href="/Security/lostpassword">我要重置密码</a>', "bad", false);
			}
			
			return Controller::curr()->redirectBack();
		}
		
		return Controller::curr()->httpError(400, 'fuck off');
		
	}
}