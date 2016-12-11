<?php use SaltedHerring\Debugger as Debugger;

class CartController extends Page_Controller {
	
	private static $url_handlers = array (
		''			=>	'index'
	);
	
	private static $allowed_actions = array(
		'checkout',
		'payment',
		'PaymentHandler'
	);
	
	public function index($request) {
		return $this->renderWith(array('CartPage', 'Page'));
	}
	
	public function getCart() {
		$member_id = Member::currentUser() ? Member::currentUserID() : session_id();
		return Utils::getCurrentCart($member_id);
	}
	
	public function checkout() {
		$member_id = Member::currentUser() ? Member::currentUserID() : session_id();
		return new CartForm($this, Utils::getCurrentCart($member_id)->ID);
	}
	
	public function Link($action = NULL) {
		return 'cart';
	}
	
	public function payment() {
		$request = $this->request;
		$may_render = false;
		if ($token = Session::get('payment_token')) {
			if ($request->getHeader('Referer') == 'https://www.nzyogo.co.nz/cart') {
				Session::clear('payment_token');
				Session::set('page_refreshable', true);
				Session::save();
				$may_render = true;
			}			
		} elseif (!empty(Session::get('page_refreshable')) && $request->getHeader('Referer') == 'https://www.nzyogo.co.nz/cart') {
			$may_render = true;
		} else {
			$may_render = false;
		}
		
		if (!$may_render) {
			return $this->renderWith(array('Page'));
		}
		
		return $this->renderWith(array('Payment', 'Page'));
	}
	
	public function PaymentHandler() {
		return new PaymentForm($this);
	}
	
	public function Title() {
		
		if ($_GET['url'] == '/cart/payment') {
			$title = '收银台';
		} else {
			$title = Translatable::get_current_locale() == 'zh_CN' ? '购物车' : 'Cart';
		}
		
		return $title;
	}
	
	public function resetForm() {
		unset($_SESSION['FormInfo']['CartForm_CartForm']['errors']);
	}
}