<?php
use SaltedHerring\Debugger;
use SaltedHerring\Currency;
use GuzzleHttp\Client;

class StoristManageController extends Page_Controller
{
    private $MCSupplierID   =   null;
    private $pageNum        =   1;
    private $sales          =   null;
    protected static $allowed_actions = array(
        // 'StoreLookupForm',
        // 'StoreOrderForm'
	);

	public function init() {

		parent::init();
        Requirements::block('themes/default/js/custom.scripts.js');
        Requirements::block('themes/default/js/components/parallax.js/parallax.min.js');
        Requirements::block('themes/default/js/components/owl.carousel/dist/owl.carousel.min.js');
		Requirements::combine_files(
	        'storist.js',
	        array(
                'themes/default/js/components/JsBarcode/dist/JsBarcode.all.min.js',
                'themes/storist/js/custom.scripts.js'
	        )
        );
        SSViewer::set_theme('storist');
	}

	public function index($request) {
        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->ClassName == 'Supplier') {
                $this->MCSupplierID =   $member->MCSupplierID;
                $this->pageNum      =   !empty($request->getVar('page')) ? $request->getVar('page') : 1;

                switch ($request->param('feature'))
                {
                    case 'products':
                        if ($request->isAjax()) {
                            return $this->renderWith('ManageProducts');
                        }
                        return $this->renderWith(array('ManageProducts', 'Page'));
                        break;
                    case 'sales':
                        if ($request->isAjax()) {
                            return $this->renderWith('ManageSales');
                        }
                        return $this->renderWith(array('ManageSales', 'Page'));
                        break;
                }
                return $this->renderWith('Page');
            }

            return $this->redirect('/member');
        }

        return $this->redirect('/signin?BackURL=' . $this->Link());
	}

    public function Title() {
        return 'Storist › Manage';
    }

    public function getFeature()
    {
        $param = $this->request->param('feature');
        if (!empty($param)) {
            return $param;
        }
        return false;
    }

    public function getSalesToday()
    {
        $sales = $this->getSales();
        $sales = $sales->filter(array('Refunded' => false));
        $amount = 0;
        foreach ($sales as $sale)
        {
            $amount += $sale->Amount(true);
        }
        return money_format("%i", $amount);
    }

    public function getSales()
    {
        if (Member::currentUser()->ClassName != 'Supplier') {
            return null;
        }

        if (empty($this->sales)) {
            $this->sales = Member::currentUser()->StoreOrders()->filter(array('Created:GreaterThan' => date('Y-m-d 00:00:00')));
        }

        return $this->sales;
    }

    public function getProducts()
    {
        if ($member = Member::currentUser()) {
            $client = new Client([
                'base_uri' => 'https://merchantcloud.leochen.co.nz/'
            ]);

            $response = $client->request(
                'GET',
                'products',
                array(
                    'query' =>  [
                                    'supplier'  =>  $this->MCSupplierID,
                                    'page'      =>  $this->pageNum
                                ]
                )
            );
             $products = json_decode($response->getBody());
             Debugger::inspect($products);
        }
    }

    public function Link($action = null)
    {
        return '/storist/v1/manage/' . $action;
    }

    public function getExchangeRate()
    {
        return Currency::exchange(1);
    }
}
