<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class ProdctAPI extends BaseRestController {

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	true,
        'delete'        =>  true
    );

	public function isAuthenticated() {
		if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators') || $member->inGroup('managers')) {
                return true;
            }
        }

		return false;
	}

    public function delete($request) {
        if ($request->isDELETE()) {
            if (!empty($request->getBody()) && $request->getBody() == Session::get('SecurityID')) {
                if ($prod_id = $request->param('ID')) {
                    $product = Versioned::get_by_stage('ProductPage', 'Stage')->byID($prod_id);
                    $product->deleteFromStage('Live');
                    $product->deleteFromStage('Stage');
                    return array(
                        'code'      =>  200,
                        'message'   =>  'product deleted'
                    );
                }
            }

        }
        return false;
    }

	public function post($request) {
        if (!empty($request->postVar('SecurityID')) && $request->postVar('SecurityID') == Session::get('SecurityID')) {
            if (empty($request->postVar('title'))) {
                return $this->httpError(401, 'Title is missing');
            }

            if (empty($request->postVar('alias'))) {
                return $this->httpError(401, 'Alias is missing');
            }

            if ($prod_id = $request->param('ID')) {
    			$prod = ProductPage::get()->byID($prod_id);
    		}

            $prod = !empty($prod) ? $prod : new ProductPage();
            $prod->Title = $request->postVar('title');
            $prod->Alias = $request->postVar('alias');
            $prod->Content = $request->postVar('content');
            $prod->writeToStage('Stage');
            if (!empty($request->param('ID'))) {
                $prod->doPublish();
            }
            return array(
                'code'          =>  200,
                'message'       =>  'new product added',
                'security_id'   =>  Session::get('SecurityID'),
                'product'       =>  $prod->format(array(
                                        'product_id'    =>  'ID',
                                        'title'         => 'Title',
                                        'alias'         => 'Alias',
                                        'content'       => 'Content',
                                        'pricings'      => 'PricingData',
                                        'variants'      => 'VariantData'
                    				))
            );
        }

		return false;
	}

	public function get($request) {

		if ($prod_id = $request->param('ID')) {
			if ($prod = Versioned::get_by_stage('ProductPage', 'Stage')->byID($prod_id)){
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
}
