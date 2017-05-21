<?php
use SaltedHerring\Debugger;

class StoristStoreController extends Page_Controller
{
    protected static $allowed_actions = array(
        'StoreLookupForm',
        'StoreOrderForm'
	);

	public function init() {
		parent::init();
        Requirements::block('themes/default/js/custom.scripts.js');
        Requirements::block('themes/default/js/components/parallax.js/parallax.min.js');
        Requirements::block('themes/default/js/components/owl.carousel/dist/owl.carousel.min.js');
		Requirements::combine_files(
	        'store.js',
	        array(
                'themes/default/js/components/JsBarcode/dist/JsBarcode.all.min.js',
	        	'themes/default/js/store.scripts.js'
	        )
        );
	}

	public function index($request) {
        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->ClassName == 'Supplier' || $member->ClassName == 'Operator') {
                return $this->renderWith('Store');
            }

            return $this->redirect('/');
        }

        return $this->redirect('/signin?BackURL=/storist/v1/store');
	}

    public function Title() {
        return 'Storist › Store';
    }

    public function StoreLookupForm()
    {
        return new StoreLookupForm($this);
    }

    public function StoreOrderForm()
    {
        return new StoreOrderForm($this);
    }

    public function getProducts() {
        $products = Versioned::get_by_stage('ProductPage', 'Stage');
        $js = 'window.all_products = [';
        $arr = array();
        foreach ($products as $product) {
            $arr[] = json_encode(array( 'product_id' => $product->ID, 'product_title' => $product->Title, 'product_alias' => $product->Alias));
        }
        $js .= implode(',', $arr);
        $js .= '];';

        return $js;
    }
}
