<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class QuickAPI extends BaseRestController
{
    private $memberID       =   null;

    private static $allowed_actions = array (
        'post'              =>  "->isAuthenticated",
        'get'               =>  "->isAuthenticated",
        'delete'            =>  "->isAuthenticated"
    );

    public function isAuthenticated()
    {
        $this->memberID     =   !empty(Member::currentUserID()) ? Member::currentUserID() : session_id();
        return $this->checkCSRF($this->request);
    }

    private function checkCSRF(&$request)
    {
        if ($request->isDELETE() || $request->isPUT()) {
            return !empty($request->getBody()) && str_replace('csrf=', '', $request->getBody()) == Session::get('SecurityID');
        } elseif ($request->isPost()) {
            return !empty($request->postVar('csrf')) && $request->postVar('csrf') == Session::get('SecurityID');
        } else {
            return !empty($request->getVar('csrf')) && $request->getVar('csrf') == Session::get('SecurityID');
        }

        return false;
    }

    public function delete($request)
    {

        return false;
    }

    public function post($request)
    {
        $ID                 =   $request->param('ID');
        $action             =   $request->param('todo');

        return $this->$action($ID);
    }

    public function get($request)
    {

        return false;
    }

    private function like($ID)
    {
        $filter = array('ProductID' => $ID);
        if ($memberID = Member::currentUserID()) {
            $filter['CustomerID'] = $memberID;
        } else {
            $filter['Session'] = session_id();
        }

        $favourite = Favourite::get()->filter($filter)->first();

        if (empty($favourite)) {
            $favourite = new Favourite();
            $favourite->ProductID = $ID;
            $favourite->write();
        } else {
            $favourite->delete();
        }

        return  [
                    'toggle_class'  =>  'is-active'
                ];
    }

    private function cart($ID)
    {
        $product                    =   ProductPage::get()->byID($ID);
        $cart                       =   Utils::getCurrentCart($this->memberID);

        if (empty($cart)) {
            $cart                   =   new Order();
            $cart->Session          =   session_id();
            if ($member = Member::currentUser()) {
                $cart->CustomerID   =   $member->ID;
            }

            $cart->write();
        }

        if ($orderitem = $cart->OrderItems()->filter(array('ProductID' => $product->ID))->first()) {
            $orderitem->Quantity    +=  1;
        } else {
            $orderitem              =   new OrderItem();
            $orderitem->ProductID   =   $product->ID;
            $orderitem->OrderID     =   $cart->ID;
            $orderitem->Quantity    =   1;
        }

        $orderitem->write();
        $cart->write();

        return  [
                    'message'       =>  'Added to cart'
                ];
    }
}
