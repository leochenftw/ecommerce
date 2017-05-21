<?php

class StoreOrder extends DataObject
{
    protected static $db = array(
        'Title'         =>  'Varchar(16)',
        'PaymentMethod' =>  'Varchar(16)'
    );

    protected static $default_sort = array('ID' => 'DESC');

    protected static $summary_fields = array(
        'Title'         =>  'Receipt number',
        'PaymentMethod' =>  'Payment method',
        'Created'       =>  'When',
        "Amount"        =>  'Amount',
        "Operator"      =>  'Operator'
    );

    protected static $has_one = array(
        'Member'        =>  'Member'
    );

    protected static $has_many = array(
        'OrderItems'	=>	'StoreOrderItem'
    );

    public function populateDefaults()
    {
        $this->Title    =   substr(sha1(mt_rand() . mt_rand()), 0, 16);
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->MemberID = Member::currentUserID();
    }

    public function Operator()
    {
        if ($this->Member()->exists()) {
            $member = $this->Member();
            return $member->FirstName . ' ' . $member->Surname;
        }
        return '- unknown operator -';
    }

    public function Amount()
    {
        if ($this->OrderItems()->exists()) {
            $orders = $this->OrderItems();
            $n = 0;
            foreach ($orders as $order)
            {
                $n += $order->AltAmount->Amount;
            }

            return '$' . number_format($n, 2, '.', ',');
        }

        return '$0.00';
    }

}
