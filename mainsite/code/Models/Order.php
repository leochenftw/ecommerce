<?php
use SaltedHerring\Debugger as Debugger;
use SaltedHerring\Grid;

class Order extends SaltedOrder {
    protected static $db = array(
        'Title'			=>	'Varchar(8)',
        'Progress'		=>	'Int',
        'Session'		=>	'Varchar(255)',
        'Surname'		=>	'Varchar(128)',
        'FirstName'		=>	'Varchar(128)',
        'Email'			=>	'Varchar(256)',
        'Phone'			=>	'Varchar(48)',
        'Address'		=>	'Text',
        'isTopupOrder'  =>  'Boolean'
    );

    protected static $extensions = array(
        'ApisedExt'
    );

    protected static $has_one = array(
        'UsingFreight'	=>	'FreightOption'
    );

    protected static $has_many = array(
        'OrderItems'	=>	'OrderItem',
        'Shipments'		=>	'Shipment'//,
        //'Payments'      =>  'SaltedPaymentModel'
    );

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = array(
        'getStatus'             =>  'Open / Close',
        'Title'                 =>  '订单号',
        'PayDateDisplay'        =>  '支付时间',
        'Amount'                =>  '金额',
        'OutstandingBalance'    =>  '付讫',
        'getCustomerFullName'   =>  '老板'
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
            $this->Title = substr($this->MerchantReference, 0, 8);
        }

        if ($this->Progress < 2 && empty($this->isTopupOrder)) {
            $this->Amount->Amount = $this->getTotal(false);
        }
    }

    public function onSaltedPaymentUpdate($status)
    {
        if ($status == 'Success' && $this->Progress < 3) {
            $this->Progress = 3;
            $this->write();

            if ($this->isTopupOrder) {
                $customer = $this->Customer();
                $customer->Credit += $this->Amount->Amount;
                $customer->write();
            }
        }
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
