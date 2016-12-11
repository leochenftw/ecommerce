<?php use SaltedHerring\Debugger as Debugger;

class Utils {
		
	public static function PassCheck($user_name, $pass) {
		$status = true;
		$message = array();
		if ($user_name == $pass) {
			$status = false;
			$message[] = 'Email and password cannot be the same!';
		}
		
		if (strlen($pass) < 6) {
			$status = false;
			$message[] = "Password too short!";
		}
	
		if (!preg_match("#[0-9]+#", $pass)) {
			$status = false;
			$message[] = "Password must include at least one number!";
		}
	
		if (!preg_match("#[a-zA-Z]+#", $pass)) {
			$status = false;
			$message[] = "Password must include at least one letter!";
		}
		return array('status' => $status, 'messages' => $message);
	}
	
	public static function getCurrentCart($member_id) {
		if (Member::currentUser()) {
			$orders = Order::get()->filter(array('Progress:LessThan' => 2, 'CustomerID' => $member_id));
		} else {
			$orders = Order::get()->filter(array('Progress:LessThan' => 2, 'Session' => $member_id));
		}
		
		return $orders->count() > 0 ? $orders->first() : null;
	}
	
	public static function getReference($member_id) {
		$curCart = self::getCurrentCart($member_id);
		return $curCart ? $curCart->Title : null;
	}
	
	public static function NumUnpaid($groupon_id, $member_id) {
		if ($open_cart = self::getCurrentCart($member_id)) {
			$items = $open_cart->OrderItems()->filter(array('GrouponID' => $groupon_id));
			
			return $items->sum('Quantity');
		}
		
		return 0;
	}
	
	public static function NumPaid($groupon_id, $member_id) {
		
		if (Member::currentUser()) {
			$orders = Order::get()->filter(array('Progress:GreaterThanOrEqual' => 2, 'CustomerID' => $member_id));
		} else {
			$orders = Order::get()->filter(array('Progress:GreaterThanOrEqual' => 2, 'Session' => $member_id));
		}
		$n = 0;
		foreach ($orders as $order) {
			$order_items = $order->OrderItems();
			foreach ($order_items as $order_item) {
				if ($order_item->GrouponID == $groupon_id) {
					$n += $order_item->Quantity;
				}
			}
		}
		return $n;
	}
}