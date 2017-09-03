<?php
use SaltedHerring\Debugger;
use SaltedHerring\Utilities;
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
        if (!$this->request->isAjax()) {
    		Requirements::combine_files(
    	        'storist.js',
    	        array(
                    'themes/default/js/components/datetimepicker/build/jquery.datetimepicker.full.min.js',
                    'themes/default/js/components/JsBarcode/dist/JsBarcode.all.min.js',
                    'themes/storist/js/components/d3/d3.min.js',
                    'themes/storist/js/modules/previewable.js',
                    'themes/storist/js/modules/operator_work.js',
                    'themes/storist/js/modules/form_operator.js',
                    'themes/storist/js/modules/form_product.js',
                    'themes/storist/js/modules/msg_box.js',
                    'themes/storist/js/templates/product-rows.js',
                    'themes/storist/js/templates/ranking-item.js',
                    'themes/storist/js/custom.scripts.js'
    	        )
            );
        }
        SSViewer::set_theme('storist');
	}

	public function index($request)
    {
        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->ClassName == 'Supplier') {
                $this->MCSupplierID =   $member->MCSupplierID;

                if (empty($this->MCSupplierID)) {
                    return $this->httpError(403, 'so long');
                }

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
                    case 'ranking':
                        if ($request->isAjax()) {
                            return $this->renderWith('ManageRank');
                        }
                        return $this->renderWith(array('ManageRank', 'Page'));
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

    private function getSum(&$list)
    {
        $n              =   0;

        if (!empty($list)) {
            foreach ($list as $item)
            {
                $n += $item->Amount(true);
            }
        }

        return $n;
    }

    public function getSalesToday()
    {
        $sales          =   $this->getSales();
        $sales          =   $sales->filter(array('Refunded' => false));
        $group          =   GroupedList::create($sales->sort('PaymentMethod'));

        $group          =   $group->groupBy('PaymentMethod');

        $cash           =   $this->getSum($group['Cash']);
        $eftpos         =   $this->getSum($group['EFTPOS']);

        $amount         =   $cash + $eftpos;

        return new ArrayData(array(
                                'Cash'      =>  number_format($cash, 2, '.', ','),
                                'EFTPOS'    =>  number_format($eftpos, 2, '.', ','),
                                'TotalRaw'  =>  number_format($amount, 2, '.', ','),
                                'Total'     =>  Utilities::shorten_number($amount, 999)
                            ));
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
                $sales = $sales->filter(array('Created:GreaterThanOrEqual' => $from . ' 00:00:00', 'Created:LessThanOrEqual' => $to . ' 23:59:59'));
            } elseif (!empty($from)) {
                $sales = $sales->filter(array('Created:GreaterThanOrEqual' => $from . ' 00:00:00'));
            } elseif (!empty($to)) {
                $sales = $sales->filter(array('Created:LessThanOrEqual' => $to . ' 23:59:59'));
            }

            if (!empty($ex_ref)) {
                $sales = $sales->exclude(array('Refunded' => true));
            }

            if (!empty($ex_suc)) {
                $sales = $sales->filter(array('Refunded' => true));
            }

            $paged = new PaginatedList($sales, $this->request);
            $paged->setPageLength(50);

            return $paged;
        }

        if (empty($this->sales)) {
            $this->sales = $sales->filter(array('Created:GreaterThanOrEqual' => date('Y-m-d 00:00:00')));
        }
        $paged = new PaginatedList($this->sales, $this->request);
        $paged->setPageLength(50);

        return $paged;
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

            $raw = json_decode($response->getBody());
            // Debugger::inspect($raw);
            $page_count = $raw->page_count;
            $item_count = $raw->item_count;
            $products = $raw->data;
            $list = array();
            foreach ($products as $product)
            {
                $update_at         =   new Datetime($product->last_update->date, new DateTimeZone($product->last_update->timezone));
                // Debugger::inspect($update_at);
                $update_at         =   $update_at->setTimezone(new DateTimeZone('Pacific/Auckland'));

                $item = array(
                    'ID'           =>  $product->id,
                    'Title'        =>  $product->title,
                    'Chinese'      =>  empty($product->chinese_title) ? '' : $product->chinese_title,
                    'Cost'         =>  empty($product->cost) ? '$0.00' : money_format("$%i", $product->cost),
                    'Price'        =>  money_format("$%i", $product->price),
                    'StockCount'   =>  empty($product->stock_count) ? '-' : $product->stock_count,
                    'Barcode'      =>  empty($product->barcode) ? '' : $product->barcode,
                    'LastUpdate'   =>  $update_at->format('Y-m-d H:i:s'),
                    'Width'        =>  $product->width,
                    'Height'       =>  $product->height,
                    'Depth'        =>  $product->depth,
                    'Measurement'  =>  $product->measurement,
                    'Weight'       =>  $product->weight,
                    'Manufacturer' =>  $product->manufacturer,
                    'Thumbnail'    =>  empty($product->thumbnail) ? null : $product->thumbnail
                );
                $list[] = $item;
            }

            $list = new ArrayList($list);
            $pagination = null;

            if ($page_count > 0) {
                $this->MakePagination($pagination, $page_count);
            }


            return new ArrayData(array('Pagination' => $pagination, 'List' => $list, 'Count' => Utilities::shorten_number($item_count)));
        }
    }

    private function MakePagination(&$pagination, $page_count)
    {
        $current_page = !empty($this->request->getVar('page')) ? $this->request->getVar('page') : 1;
        $prev = $current_page - 1;
        $next = $current_page + 1;

        $pagination = '<nav class="pagination">' . "\n";

        if ($prev < 1) {
            $pagination .= "\t" . '<a class="pagination-previous" title="This is the first page" disabled>Prev</a>' . "\n";
        } else {
            $pagination .= "\t" . '<a class="pagination-previous" href="' . $this->Link('products') . '?page=' . $prev . '">Prev</a>' . "\n";
        }

        if ($next > $page_count) {
            $pagination .= "\t" . '<a class="pagination-next" title="This is the last page" disabled>Next</a>' . "\n";
        } else {
            $pagination .= "\t" . '<a class="pagination-next" href="' . $this->Link('products') . '?page=' . $next . '">Next</a>' . "\n";
        }

        $pagination .= "\t" . '<ul class="pagination-list">' . "\n";
        $pagination .= "\t\t" . '<li><a class="pagination-link' . ($current_page == 1 ? ' is-current' : '') . '" href="' . $this->Link('products') . '?page=1">1</a></li>' . "\n";

        if ($current_page - 3 > 1) {
            $pagination .= "\t\t" . '<li><span class="pagination-ellipsis">&hellip;</span></li>' . "\n";
        }

        for ($i = $current_page - 3 ; $i < $current_page + 2; $i++)
        {
            if ($i >= 1 && $i < $page_count - 1 ) {
                $pagination .= "\t\t" . '<li><a class="pagination-link' . ($current_page == $i + 1 ? ' is-current' : '') . '" href="' . $this->Link('products') . '?page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>' . "\n";
            }
        }

        if ($current_page + 3 < $page_count) {
            $pagination .= "\t\t" . '<li><span class="pagination-ellipsis">&hellip;</span></li>' . "\n";
        }

        $pagination .= "\t\t" . '<li><a class="pagination-link' . ($current_page == $page_count ? ' is-current' : '') . '" href="' . $this->Link('products') . '?page=' . $page_count . '">' . $page_count . '</a></li>' . "\n";

        $pagination .= "\t</ul>\n</nav>";
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

    public function getAWeekago()
    {
        return date('Y-m-d', strtotime('-1 week'));
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

    public function getLastWeekSoldProducts()
    {
        if (Member::currentUser()->ClassName != 'Supplier') {
            return null;
        }


        $sales      =   Member::currentUser()->StoreOrders()->filter(array('Refunded' => false));

        $from       =   $this->request->getVar('from');
        $to         =   $this->request->getVar('to');
        $barcode    =   $this->request->getVar('barcode');

        if (!empty($barcode)) {
            return $sales->filter(array('Title' => $barcode));
        }

        $from       =   !empty($from) ? $from : (date('Y-m-d', strtotime('-1 week')) . ' 00:00:00');
        $to         =   !empty($to) ? $to : (date('Y-m-d') . ' 00:00:00');

        $sales      =   $sales->filter(array('Created:GreaterThanOrEqual' => $from, 'Created:LessThanOrEqual' => $to))->column();

        $items      =   GroupedList::create(StoreOrderItem::get()->filter(array('StoreOrderID' => $sales))->sort('AltTitle ASC'))->GroupBy('AltTitle');

        $list       =   [];

        foreach ($items as $title => $item)
        {
            $data   =   array(
                            'Barcode'   =>  $item->first()->AltBarcode,
                            'Title'     =>  $title,
                            'Chinese'   =>  $item->first()->AltChinese,
                            'Provider'  =>  $item->first()->AltProvider,
                            'Sales'     =>  0,
                            'Quantity'  =>  0
                        );

            foreach ($item as $record)
            {
                $data['Sales']      +=  $record->AltAmount->Amount;
                $data['Quantity']   +=  $record->Quantity;
            }

            $data['Sales']          =   '$' . number_format($data['Sales'], 2, '.', ',');
            $list[]                 =   $data;
        }

        return new ArrayList($list);
    }
}
