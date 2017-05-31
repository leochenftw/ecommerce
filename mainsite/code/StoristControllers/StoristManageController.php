<?php
use SaltedHerring\Debugger;
use SaltedHerring\Currency;
use GuzzleHttp\Client;

class StoristManageController extends Page_Controller
{
    private $MCSupplierID   =   null;
    private $pageNum        =   1;
    private $sales          =   null;
    private static $allowed_actions = array(
        'SupplierEditForm',
        'OperatorForm',
        'DeleteOperator'
	);

	public function init() {

		parent::init();
        Requirements::block('themes/default/js/custom.scripts.js');
        Requirements::block('themes/default/js/components/parallax.js/parallax.min.js');
        Requirements::block('themes/default/js/components/owl.carousel/dist/owl.carousel.min.js');
		Requirements::combine_files(
	        'storist.js',
	        array(
                'themes/default/js/components/datetimepicker/build/jquery.datetimepicker.full.min.js',
                'themes/default/js/components/JsBarcode/dist/JsBarcode.all.min.js',
                'themes/storist/js/modules/previewable.js',
                'themes/storist/js/modules/operator_work.js',
                'themes/storist/js/modules/form_operator.js',
                'themes/storist/js/modules/msg_box.js',
                'themes/storist/js/custom.scripts.js'
	        )
        );
        SSViewer::set_theme('storist');
	}

	public function index($request)
    {
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
                    case 'account':
                        if ($request->isAjax()) {
                            return $this->renderWith('ManageAccount');
                        }
                        return $this->renderWith(array('ManageAccount', 'Page'));
                        break;
                    case 'operators':
                        if ($request->isAjax()) {
                            return $this->renderWith('ManageOperators');
                        }
                        return $this->renderWith(array('ManageOperators', 'Page'));
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

    public function Title()
    {
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

        $sales      =   Member::currentUser()->StoreOrders();
        $from       =   $this->request->getVar('from');
        $to         =   $this->request->getVar('to');
        $ex_ref     =   $this->request->getVar('exclude_refund');
        $ex_suc     =   $this->request->getVar('exclude_success');
        $receipt    =   $this->request->getVar('receipt');

        if (!empty($receipt)) {
            return $sales->filter(array('Title' => $receipt));
        }

        if ($ex_ref && $ex_suc) {
            return new ArrayList(array());
        }

        if (!empty($from) || !empty($to) || !empty($ex_ref) || !empty($ex_suc)) {
            if (!empty($from) && !empty($to)) {
                $sales = $sales->filter(array('Created:GreaterThanOrEqual' => $from . '00:00:00', 'Created:LessThanOrEqual' => $to . '23:59:59'));
            } elseif (!empty($from)) {
                $sales = $sales->filter(array('Created:GreaterThanOrEqual' => $from . '00:00:00'));
            } elseif (!empty($to)) {
                $sales = $sales->filter(array('Created:LessThanOrEqual' => $to . '23:59:59'));
            }

            if (!empty($ex_ref)) {
                $sales = $sales->exclude(array('Refunded' => true));
            }

            if (!empty($ex_suc)) {
                $sales = $sales->filter(array('Refunded' => true));
            }

            return $sales;
        }

        if (empty($this->sales)) {
            $this->sales = $sales->filter(array('Created:GreaterThanOrEqual' => date('Y-m-d 00:00:00')));
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
             $list = array();
             foreach ($products as $product)
             {
                 $item = array(
                     'ID'           =>  $product->id,
                     'Title'        =>  $product->title,
                     'Cost'         =>  empty($product->cost) ? '$0.00' : money_format("$%i", $product->cost),
                     'Price'        =>  money_format("$%i", $product->price),
                     'StockCount'   =>  empty($product->stock_count) ? '-' : $product->stock_count,
                     'Barcode'      =>  empty($product->barcode) ? '' : $product->barcode,
                     'LastUpdate'   =>  empty($product->last_update) ? '' : $product->last_update,
                     'Thumbnail'    =>  empty($product->thumbnail) ? null : $product->thumbnail
                 );
                 $list[] = $item;
             }
            //  Debugger::inspect($products);
             return new ArrayList($list);
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

    public function getToday()
    {
        return date('Y-m-d');
    }

    public function getOperators()
    {
        if ($member = Member::currentUser())
        {
            return $member->ClassName == 'Supplier' ? $member->Operators()->sort(array('ID' => 'DESC')) : null;
        }

        return null;
    }

    public function SupplierEditForm()
    {
        return new SupplierEditForm($this);
    }

    public function DeleteOperator()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            if ($member = Member::currentUser()) {
                if ($member->ClassName == 'Supplier') {
                    $data = $this->request->postVars();
                    if (!empty($data['ID'])) {
                        if ($operator = Operator::get()->byID($data['ID'])) {
                            $member->Operators()->remove($operator);
                            $operator->delete();
                            return json_encode(array('success' => true, 'message' => 'Operator has been deleted.'));
                        }
                    }
                }
            }
        }

        return $this->httpError(404);
    }

    public function OperatorForm()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $data = $this->request->postVars();
            $password = $data['Password']['_Password'];
            $re_password = $data['Password']['_ConfirmPassword'];

            if ($password != $re_password) {
                return json_encode(array('success' => false, 'message' => 'Passwords do not match.'));
            }

            if (!empty($data['ID'])) {
                $operator = Operator::get()->byID($data['ID']);
            } else {
                if (empty($password)) {
                    return json_encode(array('success' => false, 'message' => 'Password is missing!'));
                }

                if ($test = Member::get()->filter(array('Email' => $data['Email']))->first()) {
                    return json_encode(array('success' => false, 'message' => 'This email has already been used!'));
                }

                $operator = new Operator();
            }

            if (!empty($operator)) {
                $supplier               =   Member::currentUser();

                $operator->FirstName    =   $data['FirstName'];
                $operator->Surname      =   $data['Surname'];
                $operator->Email        =   $data['Email'];

                if (!empty($password)) {
                    $operator->Password     =   $password;
                }

                $operator->write();

                $supplier->Operators()->add($operator);

                return json_encode(array('success' => true, 'message' => empty($data['ID']) ? 'Operator created' : 'Operator updated'));
            }

            return json_encode(array('success' => false, 'message' => 'Unknown error'));
        }

        return $this->httpError(404);
    }
}
