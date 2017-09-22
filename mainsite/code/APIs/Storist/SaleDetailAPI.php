<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger;
use SaltedHerring\Utilities;
use GuzzleHttp\Client;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class SaleDetailAPI extends BaseRestController
{
    private $receipt    =   null;
    private static $allowed_actions = array (
        'get'			=>	"->isAuthenticated"
    );

	public function isAuthenticated()
    {
		if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->ClassName == 'Supplier') {
                if ($this->receipt = $this->request->param('receipt')) {
                    return true;
                }
            }
        }

		return false;
	}

    public function get($request)
    {
        $list           =   [];
        $order          =   StoreOrder::get()->filter(['Title' => $this->receipt])->first();

        $list           =   $order->getDetails();


        return $list;
    }

}
