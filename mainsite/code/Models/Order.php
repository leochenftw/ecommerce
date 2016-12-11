<?php use SaltedHerring\Debugger as Debugger;

class Order extends DataObject {
	protected static $db = array(
		'Title'			=>	'Varchar(64)',
		'AmountDue'		=>	'Decimal',
		'Progress'		=>	'Int',
		'Session'		=>	'Varchar(255)',
		'Surname'		=>	'Varchar(128)',
		'FirstName'		=>	'Varchar(128)',
		'Email'			=>	'Varchar(256)',
		'Phone'			=>	'Varchar(48)',
		'Address'		=>	'Text'
	);
	
	protected static $default_sort = array(
		'ID'			=>	'DESC'
	);
	
	protected static $extensions = array(
		'ApisedExt'
	);
	
	protected static $has_one = array(
		'Customer'		=>	'Member',
		'UsingFreight'	=>	'FreightOption'
	);
	
	protected static $has_many = array(
		'OrderItems'	=>	'OrderItem',
		'Shipments'		=>	'Shipment'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab(
			'Root.Main',
			DropdownField::create('Progress', '流程', Config::inst()->get('OrderProgress', 'Steps'))
		);
		return $fields;
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (empty($this->Title)) {
			$created = new DateTime('NOW');
			$timestamp = $created->format('YmdHisu');
			$user_session = session_id();
			
			$this->Title = strtolower(substr(sha1(md5($timestamp.'-'.$user_session)), 0, 8));
		}
		
		$this->AmountDue = $this->getTotal(false);
	}
	
	public function getSum($format_output = true) {
		$sum = 0;
		
		if ($this->OrderItems()->count() == 0) {
			return $format_output ? number_format($sum, 2, '.', ',') : 0;
		}
		
		$sum = $this->OrderItems()->SumFunction('getSubtotal');
		if (!$format_output) {
			return $sum;
		}
		return number_format($sum, 2, '.', ',');
	}
	
	public function getWeight() {
		if ($this->OrderItems()->count() > 0) {
			$items = $this->OrderItems();
			$weight = 0;
			foreach ($items as $item) {
				$weight += $item->Groupon()->Product()->Weight * $item->Quantity;
			}
			
			return $weight;
		}
		
		return 0;
	}
	
	public function getFrieghtCost($format_output = true) {
		if (empty($this->UsingFreightID)) {
			$cost = 0;
		} else {
			$weight = $this->getWeight();
			$cost = $this->UsingFreight()->Price * $weight;
		}
		
		if (!$format_output) {
			return $cost;
		}
		return number_format($cost, 2, '.', ',');			
	}
	
	public function getTotal($format_output = true) {
		$sum = $this->getSum(false) + $this->getFrieghtCost(false);
		if (!$format_output) {
			return $sum;
		}
		return number_format($sum, 2, '.', ',');
	}
	
	public function getNiceProgress() {
		$status = Config::inst()->get('OrderProgress', 'Steps');
		return Translator::translate($status[$this->Progress]);
	}
}