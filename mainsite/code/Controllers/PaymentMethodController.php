<?php use SaltedHerring\Debugger as Debugger;

class PaymentMethodController extends Page_Controller {
	private static $url_handlers = array (
		''		=>	'index',
		'$ID'	=>	'index'
	);
	
	public function index($request) {
		$format = array(
			'id'			=>	'ID',
			'title'		=>	'Title',
			'content'	=>	'Content'
		);
		
		if ($payment_method_id = $request->param('ID')) {
			
			if ($payment_method = PaymentMethod::get()->byID($payment_method_id)) {
				if ($request->getVar('prerender')) {
					return $payment_method->forTemplate();
				}
				
				return json_encode(array(
						'payment_methods' => $payment_method->format($format),
						'reference_number' => Utils::getReference(Member::currentUser() ? Member::currentUserID() : session_id())
				));
			}
			
			return $this->httpError(404, 'no such payment method');
		}
		
		return json_encode(array(
					'payment_methods' => PaymentMethod::get()->format($format),
					'reference_number' => Utils::getReference(Member::currentUser() ? Member::currentUserID() : session_id())
				));
	}
}