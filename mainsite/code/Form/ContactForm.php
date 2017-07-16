<?php
use SaltedHerring\Debugger as Debugger;
use SaltedHerring\RPC as RPC;
/**
 *
 * */
class ContactForm extends Form {

	public function __construct($controller) {
		$fields = new FieldList();
		$fields->push(TextField::create('Name', '')->setAttribute('placeholder', '尊姓大名'));
		$fields->push(EmailField::create('Email', '')->setAttribute('placeholder', '电子邮箱'));
		$fields->push(TextField::create('Social', '')->setAttribute('placeholder', '微信/QQ'));
		$fields->push(TextareaField::create('Message', '老板有啥吩咐?')->setAttribute('placeholder', '老板您说着...'));

		$actions = new FieldList(
			$btnSubmit = FormAction::create('SendEmail','发送')//->performReadonlyTransformation()
		);

        $btnSubmit->addExtraClass('is-danger is-large');

		$required_fields = array(
			'Name', 'Email', 'Message'
		);

		$required = new RequiredFields($required_fields);

		parent::__construct($controller, 'ContactForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "ContactForm"));

	}

	public function SendEmail($data, $form) {
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID') && !empty($data['g-recaptcha-response'])) {
			$g_token = $data['g-recaptcha-response'];
			$result = RPC::send('https://www.google.com/recaptcha/api/siteverify', array(
				'secret'    =>  Config::inst()->get('GoogleAPIs','Recaptcha'),
				'response'  =>  $g_token
			));
			$result = json_decode($result);
			if ($result->success) {
                Debugger::inspect($result);
			}
		}

	}
}
