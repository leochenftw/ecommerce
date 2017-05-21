<?php

class Operator extends Member
{
    private static $db = array(

    );

    private static $belongs_many_many = array(
        'WorksFor'      =>  'Supplier.Operators'
    );
}
