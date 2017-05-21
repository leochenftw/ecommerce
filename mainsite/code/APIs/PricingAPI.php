<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;

class PricingAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'delete'        =>  "->isAuthenticated",
        'get'			=>	true
    );

    public function isAuthenticated()
    {
        $request = $this->request;
        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->inGroup('managers')) {
                return true;
            }
        }

        return false;
    }

    public function delete($request)
    {
        if ($deleteVars = $request->getBody()) {
            $deleteVars = json_decode($deleteVars);
            $prod_id = $deleteVars->prod_id;

            if ($pricing_id = $request->param('ID')) {
                $pricing = Pricing::get()->byID($pricing_id);
                $pricing->delete();
                $prod = Versioned::get_by_stage('ProductPage', 'Stage')->byID($prod_id);
                return $prod->format(array(
                            'product_id'    =>  'ID',
                            'title'         => 'Title',
                            'alias'         => 'Alias',
                            'barcode'       => 'Barcode',
                            'content'       => 'Content',
                            'pricings'      => 'PricingData',
                            'variants'      => 'VariantData'
                        ));
            }

        }

        return false;
    }

	public function post($request)
    {
        $cost = $request->postVar('cost');
        $price = $request->postVar('price');
        $prod_id = $request->postVar('prod_id');

        if ($pricing_id = $request->param('ID')) {
            $pricing = Pricing::get()->byID($pricing_id);
        } else {
            $pricing = new Pricing();

            if ($variant_id = $request->postVar('variant_id')) {
                $pricing->VariantPricingID = $variant_id;
            } elseif (!empty($prod_id)) {
                $pricing->ProductPricingID = $prod_id;
            }
        }

        $pricing->Cost = $cost;
        $pricing->Price = $price;

        $pricing->write();
        // return $pricing->format(array(
        //     'pricing_id'	=>	'ID',
		// 	'cost'			=>	'Cost',
		// 	'price'			=>	'Price',
		// 	'created'		=>	'Created'
        // ));
        $prod = Versioned::get_by_stage('ProductPage', 'Stage')->byID($prod_id);
        return $prod->format(array(
                    'product_id'    =>  'ID',
                    'title'         => 'Title',
                    'alias'         => 'Alias',
                    'barcode'       => 'Barcode',
                    'content'       => 'Content',
                    'pricings'      => 'PricingData',
                    'variants'      => 'VariantData'
                ));
	}

	public function get($request) {

		if ($pricing_id = $request->param('ID')) {

		} else {

        }

		return false;
    }
}
