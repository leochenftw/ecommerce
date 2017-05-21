<?php

class Supplier extends Member
{
    private static $db = array(
        'TradingName'   =>  'Varchar(128)',
        'ContactNumber' =>  'Varchar(16)',
        'GST'           =>  'Varchar(32)'
    );

    private static $has_one = array(
        'Logo'          =>  'Image'
    );

    private static $many_many = array(
        'Operators'     =>  'Operator'
    );
}
