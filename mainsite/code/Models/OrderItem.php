<?php use SaltedHerring\Debugger as Debugger;

class OrderItem extends DataObject {
	protected static $db = array(
		'Quantity'			=>	'Int'
	);
	
	protected static $summary_fields = array(
		'Customer',
		'Quantity'
	);
	
	protected static $has_one = array(
		'Groupon'			=>	'Groupon',
		'Order'				=>	'Order',
		'UsingPricing'		=>	'Pricing'
	);
	
	public function Customer() {
		return !empty($this->Order()->CustomerID) ? $this->Order()->Customer()->Email : $this->Order()->Session;
	}
	
	public function getSubtotal($format_output = false) {
		$sum = $this->UsingPricing()->Price * $this->Quantity;
		if (!$format_output) {
			return $sum;
		}
		return number_format($sum, 2, '.', ',');
	}
	
	public function FormattedSubtotal() {
		return $this->getSubtotal(true);
	}
}