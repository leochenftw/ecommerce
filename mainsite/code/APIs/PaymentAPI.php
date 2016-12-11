<?php 
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class PaymentAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	true
    );
	
	public function post($request) {
		
	}

	public function get($request) {
		$format = array(
			'id'		=>	'ID',
			'title'		=>	'Title',
			'content'	=>	'Content'
		);
		
		if ($payment_method_id = $request->param('ID')) {
			
			if ($payment_method = PaymentMethod::get()->byID($payment_method_id)) {
				return $payment_method->format($format);
			}
			
			return $this->httpError(404, 'no such payment method');
		}
		
		return PaymentMethod::get()->format($format);
    }
}
