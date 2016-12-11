<?php use SaltedHerring\Debugger as Debugger;

class ProductLandingPage extends Page {
	protected static $db = array(
		'Title'			=>	'Varchar(128)'
	);
	
	protected static $extensions = array(
		'MemcachedExt'
	);
	
	private static $translate_excluded_fields = array(
		'Content'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('Content');
		return $fields;		
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->Title = Translatable::get_current_locale() == 'zh_CN' ? '随便看看' : 'Products';
	}
}

class ProductLandingPage_Controller extends Page_Controller {
	
	public function LinkThis($var_name, $var_value = null) {
		$all_vars = $this->request->getVars();
		$attach = true;
		
		if (empty($var_value)) {
			unset($all_vars[$var_name]);
		} elseif (!empty($all_vars[$var_name]) && !empty($var_value)) {
			$all_vars[$var_name] = $var_value;
			$attach = false;
		}
		
		$link = $all_vars['url'] . '?';
		unset($all_vars['url']);
		foreach ($all_vars as $key => $value) {
			$link .= ($key . '=' . $value . '&');
		}
		
		if (!empty($var_value) && $attach) {
			$link .= $var_name . '=' .$var_value;
		}
		
		$link = rtrim(rtrim($link, '&'), '?');
		return $link;
	}
	
	public function grouponOnly() {
		return $this->request->getVar('groupon-only') == 1;
	}
	
	public function isASC() {
		return ($this->request->getVar('sort') == 'ASC' || empty($this->request->getVar('sort'))) ? true : false;
	}
	
	public function getProducts() {
		$request = $this->request;
		$pageNum = empty($request->getVar('start')) ? 0 : $request->getVar('start');
		$sort = empty($request->getVar('sort')) ? 'ASC' : $request->getVar('sort');
		$groupononly = empty($request->getVar('groupon-only')) ? 0 : $request->getVar('groupon-only');
		$factory = 'Products';
		$cache_key = strtolower($factory . '_' . $pageNum . '_' . $sort . '_' . $groupononly);
		
		$paged = $this->read_cache($factory, $cache_key);
		
		if (empty($paged)) {
			$products = Versioned::get_by_stage('ProductPage', 'Live')->sort(array('ID' => $sort));
			
			if (!empty($groupononly)) {
				$excluding = [];
				foreach ($products as $product) {
					if ($g = $product->Groupons()->first()) {
						if (!$g->Started() || $g->Finished()) {
							$excluding[] = $product->ID;
						}
					} else {
						$excluding[] = $product->ID;
					}
				}
				$products = $products->exclude('ID', $excluding);
			}
			
			$paged = new PaginatedList($products, $this->request);
			$this->save_cache($factory, $cache_key, $paged);
		}
		
		$paged->setPageLength(12);
		
		return $paged;
	}
	
	public function getCategories() {
		$categories = Category::get();
		$to_exclude = array();
		foreach ($categories as $category) {
			if ($category->Product()->count() == 0) {
				$to_exclude[] = $category->ID;
			}
		}
		return $categories->exclude('ID', $to_exclude);
	}
	
	public function getTags() {
		return Tag::get()->sort(array('Title' => 'ASC'));
	}
	
	public function getPromo() {
		if ($promo = Promotional::get()->first()) {
			$prodID = $promo->LinkTo()->SiteTreeID;
			return Versioned::get_by_stage('ProductPage', 'Live')->byID($prodID);
		}
		
		return null;
	}
	
}