<?php use SaltedHerring\Debugger as Debugger;

class GrouponActionController extends Page_Controller {
	private static $url_handlers = array (
		''	=>	'index'
	);
	
	private static $allowed_actions = array (
		'buy'
	);
	
	public function index($request) {
		return $this->httpError(404);
	}
	
	public function buy($request) {
		$member = Member::CurrentUser() ? Member::CurrentUserID() : session_id();
		
		if ($request->isPost()) {
			$prod_id = $request->postVar('product-id');
			$quantity = $request->postVar('quantity');
			$groupon_id = $request->postVar('groupon-id');
			
			$prod = Versioned::get_by_stage('ProductPage', 'Live')->byID($prod_id);
			if ($curr_yogo = $prod->getCurrentGroupon()) {
				if ($curr_yogo->ID == $groupon_id) {
					return $this->makeOrder($curr_yogo, $quantity, $member, $prod);
				}
				return $this->httpError(400, 'Nice try');
			}
			
			return $this->httpError(404);
		}
		return $this->httpError(400, 'Wrong way');
		
	}
	
	private function makeOrder($groupon, $n, $member_id, $prod) {
		if (!$groupon->Started()) {
			return json_encode(array(
						'success' => 0,
						'message' => '抢购还未开始'
				   ));
		}
		
		if ($groupon->Finished()) {
			return json_encode(array(
						'success' => 0,
						'message' => '抢购已结束'
				   ));
		}
		
		if ($n == 0) {
			return json_encode(array(
						'success' => 0,
						'message' => '别介... 赖好买一' . $prod->Measurement . '?'
				   ));
		}
		
		
		if ($groupon->Type != '限时抢购') {
			if ($n > $groupon->StockCount) {
				return json_encode(array(
						'success' => 0,
						'message' => '抱歉, 我们没那么多的库存 :/'
				   ));
			}
		}
		
		
		$prod_id = $prod->ID;
		
		$order = Utils::getCurrentCart($member_id);
		if (empty($order)) {
			$order = new Order();
			if (Member::currentUser()) {
				$order->CustomerID = $member_id;
			} else {
				$order->Session = $member_id;
			}
			$order->write();
		}
		
		$item_search = $order->OrderItems()->filter(array('GrouponID' => $groupon->ID));
		
		$item = $item_search->count() > 0 ? $item_search->first() : new OrderItem();
		$msg = '';
		
		if (empty($item->ID)) {
			$item->Quantity = $n;
			$item->GrouponID = $groupon->ID;
			$item->OrderID = $order->ID;
			$item->UsingPricingID = $prod->Pricings()->first()->ID;
			$msg = 'order created';
		} else {
			$item->Quantity += $n;
			$msg = 'order updated';
		}
		
		$item->write();
		
		return json_encode(array(
					'success'		=>	1,
					'message'		=>	$msg,
					'title'			=>	$prod->Title,
					'measurement'	=>	$prod->Measurement,
					'num_unpaid'	=>	Utils::NumUnpaid($groupon->ID, $member_id),
					'num_paid'		=>	Utils::NumPaid($groupon->ID, $member_id)
			   ));
	}
}