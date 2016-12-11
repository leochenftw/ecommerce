<?php use SaltedHerring\Debugger as Debugger;

class Transaction extends DataObject {
	protected static $db = array(
		'AmountReceived'	=>	'Decimal',
		'Reference'			=>	'Varchar(64)'
	);
	
	protected static $has_one = array(
		'Order'				=>	'Order',
		'PaymentMethod'		=>	'PaymentMethod'
	);
	
	public function onAfterWrite() {
		if ($this->AmountReceived == $this->Order()->AmountDue) {
			$order = Order::get()->byID($this->OrderID);
			
			if ($this->PaymentMethod()->Title == '优Gold') {
				$order->Progress = 3;
				
				$this->deduct($order->OrderItems());
				
				$customer = Customer::get()->byID($order->CustomerID);
				$customer->Credit -= $this->AmountReceived;
				$customer->write();
			}
			
			if ($this->PaymentMethod()->Title == '银行转账') {
				$order->Progress = 2;
			}			
			
			$order->write();
		}
	}
	
	private function deduct($order_items) {
		foreach ($order_items as $order_item) {
			$groupon = $order_item->Groupon();
			if (!empty($groupon->Type) && $groupon->Type != '限时抢购') {
				$groupon->StockCount -= $order_item->Quantity;
				$groupon->write();
			}
		}
	}
}