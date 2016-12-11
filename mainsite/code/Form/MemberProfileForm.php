<?php use SaltedHerring\Debugger as Debugger;

class MemberProfileForm extends Form {
			
	public function __construct($controller) {
		$member = Member::currentUser();
		$fields = new FieldList();
		$fields->push($email = EmailField::create('Email', '电子邮件')->setValue($member->Email)->setDescription('<a data-title="会员中心 | 修改邮箱地址" href="/member/action/email-update" class="ajax-routed">我要修改邮箱地址</a>')->performReadonlyTransformation());
		$fields->push($last = TextField::create('Surname', '尊姓')->setValue($member->Surname));
		$fields->push($first = TextField::create('FirstName', '大名')->setValue($member->FirstName));
		$fields->push($first = TextField::create('Phone', '联系电话/手机')->setValue($member->Phone));
		$fields->push($wechat = TextField::create('WeChat', '微信')->setValue($member->WeChat));
		$fields->push($qq = TextField::create('QQ', 'QQ')->setValue($member->QQ));
		
		$actions = new FieldList(
			$btnSubmit = FormAction::create('doUpdate','更新资料')
		);
			
		parent::__construct($controller, 'MemberProfileForm', $fields, $actions);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'member', "MemberProfileForm"));
	}
	
	public function doUpdate($data, $form) {
		if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
			
			if ($member = Member::currentUser()) {
				
				$form->saveInto($member);
				$member->write();
			}
			
			return Controller::curr()->redirectBack();
		}
		
		return Controller::curr()->httpError(400, 'fuck off');
		
	}
}