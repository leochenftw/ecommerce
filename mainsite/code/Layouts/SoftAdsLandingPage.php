<?php

class SoftAdsLandingPage extends Page {
	
	protected static $extensions = array(
		'MemcachedExt'
	);
	
	public function canCreate($member = NULL) {
		
		if (Versioned::get_by_stage('SoftAdsLandingPage', 'Stage')->count() == 0) {
			return true;
		}
		
		return false;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('Content');
		return $fields;
	}
	
}

class SoftAdsLandingPage_Controller extends Page_Controller {
	public function getBlogs() {
		$request = $this->request;
		$pageNum = empty($request->getVar('start')) ? 0 : $request->getVar('start');
		$factory = 'Blogs';
		$cache_key = strtolower($factory . '_' . $pageNum);
		
		$paged = $this->read_cache($factory, $cache_key);
		
		if (empty($paged)) {
			$products = Versioned::get_by_stage('SoftAdsPage', 'Live')->sort(array('ID' => 'DESC'));
			$paged = new PaginatedList($products, $this->request);
			$this->save_cache($factory, $cache_key, $paged);
		}
		
		$paged->setPageLength(8);
		
		return $paged;
	}
}