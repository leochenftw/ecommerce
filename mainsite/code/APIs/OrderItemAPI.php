<?php 
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class OrderItemAPI extends BaseRestController {

    private static $allowed_actions = array (
		'put'			=>	"->isAuthenticated",
		'delete'		=>	"->isAuthenticated",
		'post'			=>	"->isAuthenticated",
        'get'			=>	"->isAuthenticated"
    );
	
	protected function isAuthenticated() {
		$request = $this->request;
		if ($item_id = $request->param('ID')) {
			if ($orderItem = OrderItem::get()->byID($item_id)) {
			
				if ($member = Member::currentUser()) {
					
					if ($member->inGroup('administrator')) {
						return true;
					}
					$customer_id = $orderItem->Order()->CustomerID;
					return $customer_id == $member->ID;
					
				} else {
					$customer_id = $orderItem->Order()->Session;
					return $customer_id == session_id();
				}
			}
			
		}		
        return false;
    }
	
	public function put($request) {
		if ($item_id = $request->param('ID')) {
			if ($n = $request->getVar('quantity')) {
				$orderItem = OrderItem::get()->byID($item_id);
				
				$groupon = $orderItem->Groupon();
				
				if ($groupon->Type != '限时抢购') {
					$stock_count = $groupon->StockCount;
					if ($n > $stock_count) {
						return array('success' => false, 'message' => '抱歉, 我们仅剩库存: ' . $stock_count  , 'quantity' => $orderItem->Quantity); 
					}
				}
				
				
				$orderItem->Quantity = $n;
				$orderItem->write();
				$order = $orderItem->Order();
				return array('success' => true, 'message' => 'item updated', 'subtotal' => $orderItem->FormattedSubtotal(), 'data' => array('weight' => $order->getWeight(), 'total' => $order->getSum()));
			}
			return array('success' => false, 'message' => 'quantity cannot be 0');
		}
		
		return array('success' => false, 'message' => 'missing order item id');
	}
	
	public function delete($request) {
		if ($item_id = $request->param('ID')) {
			$orderItem = OrderItem::get()->byID($item_id);
			$order = Order::get()->byID($orderItem->OrderID);
			$orderItem->delete();
			if ($order->OrderItems()->count() == 0) {
				$order->delete();
			}
			return array('success' => true, 'message' => 'item removed');
		}
		
		return array('success' => false, 'message' => 'missing order item id');
	}
	
	public function post($request) {
		if ($item_id = $request->param('ID')) {
			$action = $request->postVar('action');
		}
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
