<?php 
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class AddressAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	"->isAuthenticated"
    );
	
	public function isAuthenticated() {
		$request = $this->request;
		if ($addr_id = $request->param('ID')) {
			if ($addr = Address::get()->byID($addr_id)){
				return $addr->MemberID == Member::currentUserID();
			}
		}
		
		return false;
	}
	
	public function post($request) {
		if ($addr_id = $request->param('ID')) {
			if ($addr = Address::get()->byID($addr_id)){
				$fields = $request->postVars();
				foreach ($fields as $key => $value) {
					$addr->$key = $value;
				}
				
				$addr->write();
				return true;
			}
		}
		
		return false;
	}

	public function get($request) {
		
		if ($addr_id = $request->param('ID')) {
			if ($addr = Address::get()->byID($addr_id)){
				return $addr->format(array(
							'title'		=>	'Title',
							'surname'	=>	'Surname',
							'firstname'	=>	'FirstName',
							'email'		=>	'Email',
							'phone'		=>	'Phone'
						));
			}
		}
		
		return false;
    }
}
