<?php use SaltedHerring\Debugger as Debugger;

class SoftAdsPage extends Page {
	
	protected static $default_sort = array(
		'ID'		=>	'DESC'
	);
	
	public function canCreate($member = NULL) {
		
		if (Versioned::get_by_stage('SoftAdsLandingPage', 'Live')->count() > 0) {
			return true;
		}
		
		return false;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab(
			'Root.Main',
			CheckboxSetField::create('RelatedProdct', '相关商品', Versioned::get_by_stage('ProductPage', 'Live')->map('ID', 'Title'))
		);
		
		$fields->addFieldsToTab(
			'Root.Images',
			array(
				SaltedUploader::create('Cover', '封面')->setFolderName('blogs')->setCropperRatio(1),
				UploadField::create('Images', '广告图片')->setFolderName('blogs')
			)
		);
		
		$tags = TagField::create(
			'Tags',
			'标签',
			Tag::get(),
			$this->Tags()
		)->setShouldLazyLoad(true)->setCanCreate(true);
		
		$fields->addFieldToTab('Root.Main', $tags);
		
		return $fields;
	}
		
	protected static $has_one = array(
		'Cover'			=>	'Image'
	);
	
	protected static $has_many = array(
		'Images'		=>	'Image'
	);
	
	protected static $many_many = array(
		'RelatedProdct'	=>	'ProductPage',
		'Tags'			=>	'Tag'
	);
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if ($parent = Versioned::get_by_stage('SoftAdsLandingPage', 'Live')->first()) {
			$this->ParentID = $parent->ID;
		}
		
		$this->ShowInMenus = false;
	}
	
	public function getDate() {
		$date = new Date();
		$date->setValue($this->Created);
		$date = $date->DayOfMonth();
		return $date < 10 ? ('0' . $date) : $date;
	}
	
	public function getAbstract() {
		$content = strip_tags($this->Content);
		
		return mb_substr($content, 0, 26) . (mb_strlen($content) > 26 ? '...' : '');
	}
}

class SoftAdsPage_Controller extends Page_Controller {
	public function getSimilarBlogs() {
		$tags = $this->Tags();
		$result = new ArrayList();
		foreach ($tags as $tag) {
			$blogs = $tag->Blogs()->limit(3);
			foreach ($blogs as $blog) {
				if ($result->count() + 1 <= 3) {
					$result->add($blog);
				} else {
					return $result;
				}
			}
		}
		return $result;
	}
	
	public function getPromo() {
		if ($promo = Promotional::get()->first()) {
			$prodID = $promo->LinkTo()->SiteTreeID;
			return Versioned::get_by_stage('ProductPage', 'Live')->byID($prodID);
		}
		
		return null;
	}
}