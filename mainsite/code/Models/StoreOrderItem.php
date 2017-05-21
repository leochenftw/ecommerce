<?php use SaltedHerring\Debugger as Debugger;

class StoreOrderItem extends DataObject
{

	private static $db = array(
		'Quantity'			=>	'Int',
        'MCProdID'          =>  'Int',
        'AltAmount'         =>  'Money'
	);

    private static $summary_fields = array(
        'MCProdID'          =>  'Merchant Cloud Product ID',
        'Quantity'          =>  'Quantity',
        'AltAmount'         =>  'Subtotal'
    );

    private static $has_one = array(
        'StoreOrder'        =>  'StoreOrder'
    );
}
