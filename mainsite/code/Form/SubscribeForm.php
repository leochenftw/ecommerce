<?php use SaltedHerring\Debugger as Debugger;

class SubscribeForm extends Form {
	public function __construct($controller, $action_label, $watch_on = null, $product_id = null) {
		$member = Member::currentUser();
		$fields = new FieldList();
		$fields->push($email = EmailField::create('Email', '电子邮件')->setAttribute('placeholder', 'your@email.com'));
        if (!empty($watch_on)) {
            foreach ($watch_on as $key => $value) {
                $fields->push(HiddenField::create($key, $key, $value));
            }
        }

		$actions = new FieldList(
			$btnSubmit = FormAction::create('doSubscribe', $action_label)->setUseButtonTag(true)
		);

        if ($member = Member::currentUser()) {
            $email->setValue($member->Email);
			if (!empty($product_id) && $member->isWatchingProduct($product_id)) {
				$btnSubmit->setDisabled(true)->setTitle('已关注');
			}
        }
		
		parent::__construct($controller, 'SubscribeForm', $fields, $actions);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'api/v/1/subscribe', !empty($product_id) ? $product_id : ''));
	}

	public function doSubscribe($data, $form) {
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {

		}

		return Controller::curr()->httpError(400, 'fuck off');

	}
}
