<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;

class VariantAPI extends BaseRestController {

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

            if ($variant_id = $request->param('ID')) {
                $variant = Pricing::get()->byID($variant_id);
                $variant->delete();
                $prod = ProductPage::get()->byID($prod_id);
                return $prod->format(array(
                            'product_id'    =>  'ID',
                            'title'         => 'Title',
                            'alias'         => 'Alias',
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
        // Debugger::inspect($request->postVars());
        $title = $request->postVar('title');
        $cost = $request->postVar('cost');
        $price = $request->postVar('price');
        $prod_id = $request->postVar('prod_id');

        if ($variant_id = $request->param('ID')) {
            $variant = Variant::get()->byID($variant_id);
        } else {
            $variant = new Variant();
            $variant->ProductID = $prod_id;
        }

        $variant->Title = $title;
        $variant->write();

        if (!empty($cost) || !empty($price)) {
            $pricing = new Pricing();
            $pricing->Cost = $cost;
            $pricing->Price = $price;
            $pricing->VariantPricingID = $variant->ID;
            $pricing->write();
        }

        $prod = ProductPage::get()->byID($prod_id);
        return $prod->format(array(
                    'product_id'    =>  'ID',
                    'title'         => 'Title',
                    'alias'         => 'Alias',
                    'content'       => 'Content',
                    'pricings'      => 'PricingData',
                    'variants'      => 'VariantData'
                ));
	}

	public function get($request) {

		if ($variant_id = $request->param('ID')) {

		} else {

        }

		return false;
    }
}
