<?php
use SaltedHerring\Debugger;
/**
 * @file SigninController.php
 *
 * Signin controller.
 * */
class SigninController extends Page_Controller {

	private static $url_handlers = array(
		''	=>	'index'
	);

	private static $allowed_actions = array(
		'index',
		'SigninForm'
	);

	public function index($request) {
		$curr_member = Member::currentUser();
		$backURL = $request->getVar('BackURL') ? $request->getVar('BackURL') : '/member';
        
		if ($curr_member) {
			$this->redirect($backURL);
		}
		return $this->renderWith(array('SigninForm', 'Page'));
	}

	public function SigninForm() {
		return new SigninForm($this, 'SigninForm');
	}

	public function Title() {
		return '会员登录';
	}
}
