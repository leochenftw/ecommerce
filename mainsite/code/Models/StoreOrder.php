<?php

use SaltedHerring\Debugger;
use GuzzleHttp\Client;

class StoreOrder extends DataObject
{
    protected static $db = array(
        'Title'         =>  'Varchar(16)',
        'PaymentMethod' =>  'Varchar(16)',
        'Refunded'      =>  'Boolean',
        'Sold'          =>  'Boolean'
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
        'Member'        =>  'Member',
        'Supplier'      =>  'Member'
    );

    protected static $has_many = array(
        'OrderItems'	=>	'StoreOrderItem'
    );

    public function populateDefaults()
    {
        $this->Title    =   substr(sha1(mt_rand() . mt_rand() . time() . Member::currentUserID()), 0, 16);
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->exists()) {
            $this->MemberID = Member::currentUserID();
            $member = Member::currentUser();
        } else {
            $member = $this->Member();
        }

        if ($member->ClassName == 'Operator') {
            $supplier = $member->WorksFor()->first();
            $this->SupplierID = !empty($supplier) ? $supplier->ID : null;
        } elseif ($member->ClassName == 'Supplier') {
            $this->SupplierID = $this->MemberID;
        } else {
            $this->SupplierID = null;
        }

    }

    /**
     * Event handler called after writing to the database.
     */
    public function onAfterWrite()
    {
        parent::onAfterWrite();

        if ($this->Supplier()->exists()) {
            if ($supplier_id = $this->Supplier()->MCSupplierID) {
                if ($this->Sold) {
                    $items = $this->OrderItems()->map('MCProdID', 'Quantity')->toArray();

                    $client = new Client([
                        'base_uri' => 'https://merchantcloud.leochen.co.nz/'
                    ]);

                    $data = [
                        'multipart' =>  [
                            [
                                'name'      =>  'products',
                                'contents'  =>  json_encode($items)
                            ],
                            [
                                'name'      =>  'supplier_id',
                                'contents'  =>  $supplier_id
                            ]
                        ]
                    ];

                    $response = $client->request(
                        'POST',
                        'stocks/minus',
                        $data
                    );
                    //
                    // Debugger::inspect(json_decode($response->getBody()));
                    //
                    // return json_decode($response->getBody());
                }
            }
        }
    }

    public function Operator()
    {
        if ($this->Member()->exists()) {
            $member = $this->Member();
            return $member->FirstName . ' ' . $member->Surname;
        }
        return '- unknown operator -';
    }

    public function Amount($returnFloat = false)
    {
        if ($this->OrderItems()->exists()) {
            $orders = $this->OrderItems();
            $n = 0;
            foreach ($orders as $order)
            {
                $n += $order->AltAmount->Amount;
            }

            return $returnFloat ? $n : '$' . number_format($n, 2, '.', ',');
        }

        return $returnFloat ? 0 : '$0.00';
    }

}
