<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger;
use SaltedHerring\Utilities;
use GuzzleHttp\Client;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class RankingAPI extends BaseRestController
{

    private static $allowed_actions = array (
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
        $from       =   $request->getVar('from');
        $to         =   $request->getVar('to');
        $by         =   $request->getVar('by');
        $from       =   !empty($from) ? $from : (date('Y-m-d', strtotime('-1 week')) . ' 00:00:00');
        $to         =   !empty($to) ? $to : (date('Y-m-d') . ' 00:00:00');
        $filter     =   [
                            'Created:GreaterThanOrEqual' => $from,
                            'Created:LessThanOrEqual' => $to
                        ];

        if ($barcode = $request->param('Barcode')) {

            if ($by == 'manufacturer') {
                $filter['AltProvider']          =   $barcode;
            } else {
                $filter['AltBarcode']           =   $barcode;
            }

            $items      =   StoreOrderItem::get()->filter($filter);

            if ($by == 'manufacturer') {
                $group  =   GroupedList::create($items->sort('AltTitle'))->GroupBy('AltTitle');
                $list   =   [];
                foreach ($group as $key => $value)
                {
                    $data   =   [
                                    'Barcode'   =>  $value->first()->AltBarcode,
                                    'Title'     =>  $value->first()->AltTitle,
                                    'Chinese'   =>  $value->first()->AltChinese,
                                    'Provider'  =>  $value->first()->AltProvider,
                                    'Sales'     =>  0,
                                    'Quantity'  =>  0
                                ];

                    foreach ($value as $item)
                    {
                        $data['Sales']          +=  $item->AltAmount->Amount;
                        $data['Quantity']       +=  $item->Quantity;
                    }

                    $data['Sales']              =   '$' . number_format($data['Sales'], 2, '.', ',');

                    $list[] =   $data;
                }

                return $list;

            }

            if ($items->count() > 0) {
                $list       =   [];
                $data       =   array(
                                    'Barcode'   =>  $items->first()->AltBarcode,
                                    'Title'     =>  $items->first()->AltTitle,
                                    'Chinese'   =>  $items->first()->AltChinese,
                                    'Provider'  =>  $items->first()->AltProvider,
                                    'Sales'     =>  0,
                                    'Quantity'  =>  0
                                );

                foreach ($items as $item)
                {
                    $data['Sales']              +=  $item->AltAmount->Amount;
                    $data['Quantity']           +=  $item->Quantity;
                }

                $data['Sales']                  =   '$' . number_format($data['Sales'], 2, '.', ',');

                return $data;
            }

            $item       =   StoreOrderItem::get()->filter(array('AltBarcode' => $barcode))->first();

            return  [
                        'Barcode'   =>  $item->AltBarcode,
                        'Title'     =>  $item->AltTitle,
                        'Chinese'   =>  $item->AltChinese,
                        'Provider'  =>  $item->AltProvider,
                        'Sales'     =>  0,
                        'Quantity'  =>  0
                    ];
        }

        $items      =   StoreOrderItem::get()->filter($filter);

        $group  =   GroupedList::create($items->sort('AltTitle'))->GroupBy('AltTitle');
        $list   =   [];
        foreach ($group as $key => $value)
        {
            $data   =   [
                            'Barcode'   =>  $value->first()->AltBarcode,
                            'Title'     =>  $value->first()->AltTitle,
                            'Chinese'   =>  $value->first()->AltChinese,
                            'Provider'  =>  $value->first()->AltProvider,
                            'Sales'     =>  0,
                            'Quantity'  =>  0
                        ];

            foreach ($value as $item)
            {
                $data['Sales']          +=  $item->AltAmount->Amount;
                $data['Quantity']       +=  $item->Quantity;
            }

            $data['Sales']              =   '$' . number_format($data['Sales'], 2, '.', ',');

            $list[] =   $data;
        }

        usort($list, function($a, $b)
        {
            return strcmp( $b['Quantity'], $a['Quantity']);
        });

        return $list;
    }

}
