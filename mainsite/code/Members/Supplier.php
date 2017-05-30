<?php

class Supplier extends Member
{
    private static $db = array(
        'TradingName'   =>  'Varchar(128)',
        'NZLocation'    =>  'Text',
        'ContactNumber' =>  'Varchar(16)',
        'GST'           =>  'Varchar(32)',
        'MCSupplierID'  =>  'Int'
    );

    private static $has_one = array(
        'Logo'          =>  'Image'
    );

    private static $many_many = array(
        'Operators'     =>  'Operator'
    );

    private static $has_many = array(
        'StoreOrders'   =>  'StoreOrder.Supplier'
    );
}
