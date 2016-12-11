<?php 
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class FreightAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	true
    );
	
	public function post($request) {
		
	}

	public function get($request) {
		
		if ($freight_id = $request->param('ID')) {
			if ($freight = FreightOption::get()->byID($freight_id)){
				$member_id = Member::currentUser() ? Member::currentUserID() : session_id();
				if ($order = Utils::getCurrentCart($member_id)) {
					return array(
								'price'			=>	$freight->Price,
								'kg'			=>	$order->getWeight(),
								'freight_total'	=>	$order->getWeight() * $freight->Price,
								'total'			=>	$order->getWeight() * $freight->Price + $order->getSum(false)
							);
				}
				return $freight->format();
			}
			return false;
		}
		
		return false;
    }
}
