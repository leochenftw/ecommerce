<?php use SaltedHerring\Debugger as Debugger;

class HomePage extends Page {
	
}

class HomePage_Controller extends Page_Controller {
	public function getPromotionals() {
		$promos = Promotional::get();
		return $promos;
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
		return Tag::get()->sort("Rand()")->limit(20);
	}
	
	public function getHotProducts() {
		return Versioned::get_by_stage('ProductPage', 'Live')->filter(array('isHotSale' => true))->limit(5);
	}
	
	public function getUpcomingGroupon() {
		$groupons = Groupon::get()->filter(array('Start:GreaterThan' => time()))->limit(1);
		return $groupons->first();
	}
}