<?php

class StoristProductController extends Page_Controller
{
    protected static $allowed_actions = array(
		''      =>      'index'
	);

	public function init() {
		parent::init();
        Requirements::block('themes/default/js/custom.scripts.js');
        Requirements::block('themes/default/js/components/parallax.js/parallax.min.js');
        Requirements::block('themes/default/js/components/owl.carousel/dist/owl.carousel.min.js');
		Requirements::combine_files(
	        'reactful.js',
	        array(
	        	'themes/default/js/components/react/react.js',
	            'themes/default/js/components/react/react-dom.js',
                'themes/default/js/components/classnames/index.js',
	            'themes/default/js/lib/browser.min.js'
	        )
        );
	}

	public function index($request) {
        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->inGroup('managers')) {
                return $this->renderWith('Reactful');
            }
        }

        return $this->redirect('/signin?BackURL=/storist/v1/products');
	}

    public function Title() {
        return 'Storist › Products';
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
