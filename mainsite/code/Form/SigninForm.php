<?php use SaltedHerring\Debugger as Debugger;
/**
 * 
 * */
class SigninForm extends MemberLoginForm {
	
	protected $template = 'Includes/SigninForm';
	
	public function __construct($controller, $name) {
		parent::__construct($controller, $name);
		
		$fields = parent::Fields();
		if ($remember = $fields->fieldByName('Remember')) {
			$remember->setTitle('下次刷脸');
		}
		
		$this->setFormMethod('POST',true);
		$this->setFormAction(Controller::join_links(BASE_URL, "signin", "SigninForm"));
	}
	
	private static $allowed_actions = array(
		'dologin'
	);
	
	public function dologin($data) {
         if($this->performLogin($data)) {
             $this->logInUserAndRedirect($data);
         } else {
             if(array_key_exists('Email', $data)){
                 Session::set('SessionForms.MemberLoginForm.Email', $data['Email']);
                 Session::set('SessionForms.MemberLoginForm.Remember', isset($data['Remember']));
             }
			 
			 $this->sessionMessage('登录不成功. 您输入的电子邮件或密码有误. 如果您忘了密码, 可以<a href="/Security/lostpassword">尝试重置</a>.', 'bad', false);
 
             if(isset($_REQUEST['BackURL'])) $backURL = $_REQUEST['BackURL'];
             else $backURL = null;
 
             if($backURL) Session::set('BackURL', $backURL);
 
             // Show the right tab on failed login
             $loginLink = Director::absoluteURL('/signin');
             if($backURL) $loginLink .= '?BackURL=' . urlencode($backURL);
             $this->controller->redirect($loginLink);
         }
	}
}