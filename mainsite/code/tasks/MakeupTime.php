<?php
use SaltedHerring\Debugger;
use GuzzleHttp\Client;
class MakeupTime extends BuildTask
{
	protected $title = 'Makeup time';
	protected $description = 'Grab product name from Merchant Cloud, and put it to the StoreItem table';

	protected $enabled = true;

	public function run($request)
    {
        if ($request->getHeader('User-Agent') != 'CLI') {
            print '<span style="color: red; font-weight: bold; font-size: 24px;">This task is for CLI use only</span><br />';
            print '<em style="font-size: 14px;"><strong>Usage</strong>: sake dev/tasks/MakeupTime</em>';
            return;
        }

        $items  =   StoreOrderItem::get();
        $n      =   0;
        foreach ($items as $item)
        {
            $id =   $item->MCProdID;
            if (empty($id) || !empty($item->AltBarcode)) {
                print 'Merchant cloud product ID missing or already been filled - skip';
                print PHP_EOL;
                continue;
            }

            $client = new Client([
                'base_uri' => 'https://merchantcloud.leochen.co.nz/'
            ]);

            $response = $client->request(
                'GET',
                'products',
                array(
                    'query' => ['prod_id' => $id]
                )
            );

            $data = json_decode($response->getBody());

            $item->AltBarcode   =   $data->barcode;
            $item->AltTitle     =   $data->title;
            $item->AltChinese   =   $data->chinese_title;
            $item->AltProvider  =   $data->manufacturer;
            $item->write();

            print $item->AltTitle . ' updated';
            print PHP_EOL;
            $n++;
        }

        print 'Total: ' . $n . ' store order items\' details have been updated';
        print PHP_EOL;
    }
}
