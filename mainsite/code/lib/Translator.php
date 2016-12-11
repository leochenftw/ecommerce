<?php 

class Translator {
	
	const SCRIPTS = array(
		'In Cart' 				=>  '尚未付款',
		'upon Payment' 			=>  '准备付款',
		'Paid'  				=>  '付讫',
		'Payment Received'  	=>  '确认收款',
		'Shipped' 				=>  '已发货',
		'Shipped (not paid)'  	=>  '已发货(尚未付款)',
		'Completed' 			=>  '完成',
		'Refunded'  			=>  '已退款'
	);
	
	public static function translate($str) {
		if (!array_key_exists($str, self::SCRIPTS)) {
			return $str;
		}
		
		return self::SCRIPTS[$str];
	}
}