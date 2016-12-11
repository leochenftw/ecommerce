<?php use SaltedHerring\Debugger as Debugger;

class SignupController extends Page_Controller {
	
	private static $url_handlers = array (
		''			=>	'index'
	);
	
	private static $allowed_actions = array(
		'SignupForm',
		'activate'
	);
	
	public function activate($request) {
		$member_id = $request->getVar('id');
		$key = $request->getVar('token');
		if ($member = Customer::get()->byID($member_id)) {
			if (empty($member->ValidationKey)) {
				$content = 'Already activated';
			} elseif ($member->ValidationKey == $key) {
				$content = 'Your account has been activated. Your will be redirected in a few seconds...';
				$member->ValidationKey = null;
				$member->write();
				$member->login();
			} else {
				$content = "not match!";
			}
		} else {
			$content = 'No such member';
		}
		
		return $this->customise(new ArrayData(array('Title' => 'Activation', 'Content' => $content)))->renderWith(array('Page'));
	}
	
	public function index($request) {
		if ($member = Member::currentUser()) {
			return $this->redirect('/member');
		}
		
		return $this->renderWith(array('SignupForm', 'Page'));
	}
	
	public function SignupForm() {
		return new SignupForm($this);
	}
	
	public function Link($action = NULL) {
		return 'signup';
	}
	
	public function Title() {
		return '会员注册';
	}
}