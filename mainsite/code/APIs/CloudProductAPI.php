<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger;
use GuzzleHttp\Client;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class CloudProductAPI extends BaseRestController
{

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	"->isAuthenticated"
    );

	public function isAuthenticated()
    {
		if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->ClassName == 'Supplier') {
                return true;
            }
        }

		return false;
	}

    public function get($request)
    {
        if ($lookup = $request->getVar('barcode')) {
            $client = new Client([
                'base_uri' => 'https://merchantcloud.leochen.co.nz/'
            ]);

            $response = $client->request(
                'GET',
                'products',
                array(
                    'query' => ['barcode' => $lookup, 'detailed' => true]
                )
            );

            return json_decode($response->getBody());
        }

        return array(
                    'code'      =>  400,
                    'message'   =>  'Barcode is missing.'
                );
    }

    // public function delete($request) {
    //     if ($request->isDELETE()) {
    //         if (!empty($request->getBody()) && $request->getBody() == Session::get('SecurityID')) {
    //             if ($prod_id = $request->param('ID')) {
    //
    //                 return array(
    //                     'code'      =>  200,
    //                     'message'   =>  'product deleted'
    //                 );
    //             }
    //         }
    //
    //     }
    //     return false;
    // }

	public function post($request)
    {
        if (!empty(Member::currentUser()->MCSupplierID)) {

            $title          =   $request->postVar('title');
            $chinese_title  =   $request->postVar('chinese_title');
            $barcode        =   $request->postVar('barcode');
            $measurement    =   $request->postVar('measurement');
            $width          =   $request->postVar('width');
            $height         =   $request->postVar('height');
            $depth          =   $request->postVar('depth');
            $weight         =   $request->postVar('weight');
            $stock_count    =   $request->postVar('stock_count');
            $cost           =   $request->postVar('cost');
            $price          =   $request->postVar('price');
            $manufacturer   =   $request->postVar('manufacturer');
            $supplier_id    =   Member::currentUser()->MCSupplierID;


            $client = new Client([
                'base_uri' => 'https://merchantcloud.leochen.co.nz/'
            ]);

            $data = [
                'multipart' =>  [
                    [
                        'name'      =>  'title',
                        'contents'  =>  $title
                    ],
                    [
                        'name'      =>  'barcode',
                        'contents'  =>  $barcode
                    ],
                    [
                        'name'      =>  'measurement',
                        'contents'  =>  $measurement
                    ],
                    [
                        'name'      =>  'width',
                        'contents'  =>  $width
                    ],
                    [
                        'name'      =>  'height',
                        'contents'  =>  $height
                    ],
                    [
                        'name'      =>  'depth',
                        'contents'  =>  $depth
                    ],
                    [
                        'name'      =>  'weight',
                        'contents'  =>  $weight
                    ],
                    [
                        'name'      =>  'stock_count',
                        'contents'  =>  $stock_count
                    ],
                    [
                        'name'      =>  'supplier_id',
                        'contents'  =>  $supplier_id
                    ],
                    [
                        'name'      =>  'manufacturer',
                        'contents'  =>  $manufacturer
                    ],
                    [
                        'name'      =>  'cost',
                        'contents'  =>  !empty($cost) ? str_replace('$', '', $cost) : 0
                    ],
                    [
                        'name'      =>  'price',
                        'contents'  =>  !empty($price) ? str_replace('$', '', $price) : 0
                    ],
                    [
                        'name'      =>  'chinese_title',
                        'contents'  =>  $chinese_title
                    ]
                ]
            ];

            $response = $client->request(
                'POST',
                'products/' . (empty($request->param('ID')) ? '0' :  $request->param('ID')),
                $data
            );

            return json_decode($response->getBody());

            // if ($prod_id = $request->param('ID')) {
            //
            // } else {
            //     return  array(
            //                 'title'         =>  $title,
            //                 'chinese_title' =>  $chinese,
            //                 'barcode'       =>  $barcode,
            //                 'measurement'   =>  $measurement,
            //                 'width'         =>  $width,
            //                 'height'        =>  $height,
            //                 'depth'         =>  $depth,
            //                 'weight'        =>  $weight,
            //                 'stock_count'   =>  $stockcount,
            //                 'manufacturer'  =>  $manufacturer
            //             );
            // }
        }

		return array(
                    'code'      =>  400,
                    'message'   =>  'You do not have a merchant cloud id.'
                );
	}
}
