<?php
use SaltedHerring\Debugger;
use GuzzleHttp\Client;

class StoreOrderItem extends DataObject
{

	private static $db = array(
		'Quantity'			=>	'Int',
        'MCProdID'          =>  'Int',
        'AltBarcode'        =>  'Varchar(32)',
        'AltTitle'          =>  'Varchar(128)',
        'AltChinese'        =>  'Varchar(255)',
        'AltProvider'       =>  'Varchar(128)',
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

    /**
     * Event handler called after writing to the database.
     */
    public function onAfterWrite()
    {
        parent::onAfterWrite();
        if (empty($this->AltBarcode)) {
            $client = new Client([
                'base_uri' => 'https://merchantcloud.leochen.co.nz/'
            ]);

            $response = $client->request(
                'GET',
                'products',
                array(
                    'query' => ['prod_id' => $this->MCProdID]
                )
            );

            $data = json_decode($response->getBody());

            $this->AltBarcode   =   $data->barcode;
            $this->AltTitle     =   $data->title;
            $this->AltChinese   =   $data->chinese_title;
            $this->AltProvider  =   $data->manufacturer;
            $this->write();
        }
    }
}
