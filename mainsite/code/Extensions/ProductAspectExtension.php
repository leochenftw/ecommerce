<?php

use SaltedHerring\Debugger;
use GuzzleHttp\Client;

class ProductAspectExtension extends DataExtension
{
    private static $db = array(
		'Chinese'		=>	'Varchar(128)',
        'Measurement'   =>	'Varchar(16)',
        'Weight'		=>	'Decimal',
        'Width'         =>  'Decimal',
        'Height'        =>  'Decimal',
        'Depth'         =>  'Decimal',
        'MCProductID'   =>  'Int',
        'Price'         =>  'Decimal'
	);

    private static $many_many = array(
        'Suppliers'     =>  'Supplier'
	);

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $request = Controller::curr()->request;
        if ($barcode = $request->postVar('Barcode')) {
            if (!empty(trim($barcode))) {
                $data = $this->getMCData($barcode);
                // Debugger::inspect($data);
                if (!empty($data)) {
                    $this->owner->Title         =   $data->Title;
                    $this->owner->Chinese       =   $data->Chinese;
                    $this->owner->Measurement   =   $data->Measurement;
                    $this->owner->Weight        =   $data->Weight;
                    $this->owner->Width         =   $data->Width;
                    $this->owner->Height        =   $data->Height;
                    $this->owner->Depth         =   $data->Depth;
                    $this->owner->MCProductID   =   $data->MCProductID;
                    $this->owner->Price         =   $data->Price;
                    if (!empty($data->SupplierID)) {
                        if ($supplier = Supplier::get()->filter(array('MCSupplierID' => $data->SupplierID))->first()) {
                            $this->SupplierID = $supplier->ID;
                        }
                    }
                }
            }
        }
    }

    private function getMCData($barcode)
    {
        $client = new Client([
            'base_uri' => 'https://merchantcloud.leochen.co.nz/'
        ]);

        $response = $client->request(
            'GET',
            'products',
            array(
                'query' => ['barcode' => $barcode, 'yogo' => true]
            )
        );

        return json_decode($response->getBody());
    }

    public function updateCMSFields(FieldList $fields)
    {
        // Debugger::inspect(BASE_PATH . '/mainsite/js/cms.js');
        Requirements::combine_files(
            'cms.min.js',
            array(
                 'mainsite/js/cms.js'
            )
        );

        $fields->addFieldToTab(
            'Root.Main',
            TextField::create(
                'Barcode',
                '输入条形码，搜索Merchant Cloud数据库'
            ),
            'Title'
        );

        $fields->addFieldsToTab('Root.Main', array(
			TextField::create('Chinese', '中文名称')
		), 'Title');

        $fields->addFieldsToTab('Root.Main', array(
			TextField::create('Price', '基础售价')->setDescription('NZD')
		));

        $fields->addFieldToTab(
            'Root.MerchantCloud',
            TextField::create('MCProductID', 'Merchant Cloud 货品 ID')
        );

        $fields->addFieldsToTab('Root.Measurements', array(
            DropdownField::create('Measurement', '单位', Config::inst()->get('ProductPage', 'Measurements'))->setEmptyString('- 选一个 -'),
            NumericField::create('Width', '长')->setDescription('cm'),
            NumericField::create('Height', '宽')->setDescription('cm'),
            NumericField::create('Depth', '高')->setDescription('cm'),
			NumericField::create('Weight', '单位重量')->setDescription('kg')
		));

        $fields->fieldByName('Root.Measurements')->setTitle('规格');
    }
}
