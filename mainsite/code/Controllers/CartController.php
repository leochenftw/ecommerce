<?php
use SaltedHerring\Debugger as Debugger;
use SaltedHerring\SaltedPayment\API\Poli;
class CartController extends Page_Controller {

    private static $url_handlers = array (
        // 'checkout/success' => 'success'
    );

    private static $allowed_actions = array(
        'CartForm',
        'checkout',
        'PaymentHandler'
    );

    public function index($request) {
        return $this->renderWith(array('CartPage', 'Page'));
    }

    public function getCart() {
        $member_id = Member::currentUser() ? Member::currentUserID() : session_id();
        return Utils::getCurrentCart($member_id);
    }

    public function CartForm() {
        $member_id = Member::currentUser() ? Member::currentUserID() : session_id();
        return new CartForm($this, Utils::getCurrentCart($member_id)->ID);
    }

    public function Link($action = NULL) {
        return 'cart';
    }

    public function checkout() {
        $request = $this->request;
        $this->BodyClass = 'checkout';
        if (empty($request->param('ID'))) {
            $may_render = false;
            if ($token = Session::get('payment_token')) {
                if ($request->getHeader('Referer') == 'https://www.nzyogo.co.nz/cart') {
                    Session::clear('payment_token');
                    Session::set('page_refreshable', true);
                    Session::save();
                    $may_render = true;
                    return $this->renderWith(array('Checkout', 'Page'));
                }
            } elseif (!empty(Session::get('page_refreshable')) && $request->getHeader('Referer') == 'https://www.nzyogo.co.nz/cart') {
                $may_render = true;
                return $this->renderWith(array('Checkout', 'Page'));
            }

            return $this->redirect('/cart');

        } else {
            $followup = $request->param('ID');
            if ($order_id = $request->getVar('order_id')) {
                $order = Order::get()->byID($order_id);
            }

            // Debugger::inspect($order);

            if ($followup == 'success') {
                if ($order->isTopupOrder) {
                    $this->setMessage('good', '充值成功！');
                    return $this->redirect('/member/action/yo-gold');
                }
                return $this->customise(new ArrayData(array('Order' => $order)))->renderWith(array('Checkout_Successful', 'Page'));
            } elseif ($followup == 'fail') {
                return $this->renderWith(array('Checkout_Fail', 'Page'));
            } elseif ($followup == 'cancel') {
                return $this->renderWith(array('Checkout_Cancel', 'Page'));
            }
        }

        return $this->renderWith(array('Page'));
    }

    public function PaymentHandler() {
        return new PaymentForm($this);
    }

    public function Title() {

        if ($_GET['url'] == '/cart/payment') {
            $title = '收银台';
        } else {
            $title = Translatable::get_current_locale() == 'zh_Hans' ? '购物车' : 'Cart';
        }

        return $title;
    }

    public function resetForm() {
        unset($_SESSION['FormInfo']['CartForm_CartForm']['errors']);
    }
}
