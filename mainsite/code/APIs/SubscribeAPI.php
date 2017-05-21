<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class SubscribeAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated"
    );

	public function isAuthenticated() {
		$request = $this->request;

		return true;
	}

	public function post($request) {

		if ($product_id = $request->param('ID')) {
        	$product = Versioned::get_by_stage('ProductPage', 'Stage')->byID($product_id);
            if ($watch_id = $request->postVar('WatchID')) {
                $watch = Watch::get()->byID($watch_id);
                $watch->WatchInventory = !empty($request->postVar('WatchInventory')) ? $request->postVar('WatchInventory') : false;
                $watch->WatchPromo = !empty($request->postVar('WatchPromo')) ? $request->postVar('WatchPromo') : false;
                $watch->write();
                return array(
    						'success'	=>	true,
    						'message'	=>	'updated'
    					);
            } else {
    			$what_to_watch = $request->postVar('watch_on');
    			if ($member = Member::currentUser()) {
    				$this->watch_it($what_to_watch, $product_id, $member);
    			} elseif ($email = $request->postVar('Email')) {
    				$this->watch_it($what_to_watch, $product_id, null, $email);
    			} else {
    				return array(
    							'success'	=>	false,
    							'message'	=>	'请输入邮箱地址'
    						);
    			}

    			return array(
    						'success'	=>	true,
    						'message'	=>	'您已密切关注<strong>' . $product->Title . '</strong>的动态'
    					);
            }
		}

		//join the big subscriber list

		return false;
	}

	private function watch_it($what_to_watch, $product_id = null, $member = null, $email = null) {
        if (!empty($email) && empty($member)) {
            $member = Member::get()->filter(array('Email' => $email))->first();
        } elseif (!empty($member)) {
            $email = $member->Email;
        }

		if (Watch::get()->filter(array('Email' => $email))->count() > 0) {
			return;
		}

		$watch = new Watch();
        if (!empty($member)) {
            $watch->WatcherID = $member->ID;
        } else {
            $watch->Email = $email;
        }

        if (!empty($product_id)) {
		    $watch->WatchingID = $product_id;
        }

		if (!empty($what_to_watch)) {
			$watch->$what_to_watch = true;
		} else {
			$watch->WatchInventory = true;
			$watch->WatchPromo = true;
		}

		$watch->write();
	}

}
