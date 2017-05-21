<?php
use SaltedHerring\Debugger as Debugger;
use SaltedHerring\Grid as Grid;

class ProductPage extends Page {
	protected static $db = array(
		'Title'			=>	'Varchar(128)',
		'Alias'			=>	'Varchar(128)',
        'Barcode'       =>  'Varchar(128)',
		'Measurement'	=>	'Varchar(16)',
		'Weight'		=>	'Decimal',
		'isHotSale'		=>	'Boolean',
		'StockCount'    =>	'Int',
		'AcceptOrder'	=>	'Boolean',
        'Supplier'      =>  'Varchar(128)'
	);

	protected static $has_one = array(
		'Poster'		=>	'Image',
		'VPoster'		=>	'Image',
		'Square'		=>	'Image',
		'Category'		=>	'Category'
	);

	protected static $has_many = array(
		'Groupons'		=>	'Groupon',
		'ProductPhotos'	=>	'Image.ProductPhoto',
		'ProductDescs'	=>	'Image.ProductDesc',
		'Variants'		=>	'Variant',
		'Pricings'		=>	'Pricing',
		'Watches'		=>	'Watch.Watching'
	);

	protected static $many_many = array(
		'Tags'			=>	'Tag'
	);

	protected static $summary_fields = array(
		'inCategory',
		'thePoster',
		'Title',
		'Price',
		'Weight',
		'Published'
	);

	protected static $defaults = array(
		'AcceptOrder'	=>	true
	);

	protected static $extensions = array(
		'ApisedExt'
	);

    public function inStock()
    {
        return $this->StockCount > 0;
    }

	public function thePoster() {
		return $this->Poster()->FillMax(30,30);
	}

	public function Price() {
		return '$' . ($this->Pricings()->first() ? $this->Pricings()->first()->Price : '0.00');
	}

	public function inCategory() {
		return $this->Category()->Title;
	}

	public function Published() {
		return $this->isPublished() ? 'Yes' : 'No';
	}

	public function isPublished() {
		if (!empty(Versioned::get_by_stage('ProductPage', 'Live')->byID($this->ID))) {
			return true;
		}
		return false;
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$tags = TagField::create(
			'Tags',
			'标签',
			Tag::get(),
			$this->Tags()
		)->setShouldLazyLoad(true)->setCanCreate(true);
		$fields->addFieldsToTab('Root.Main', array(
			TextField::create('Alias', '英文名称'),
            TextField::create('Supplier', '供货方'),
            TextField::create('Barcode', '条形码'),
			TextField::create('StockCount', '存货'),
			CheckboxField::create('AcceptOrder', '接受订购')->setDescription('关闭以后只能在抢购期间接受订单'),
			CheckboxField::create('isHotSale', '设为热卖商品'),
			DropdownField::create('CategoryID', '分类', Category::get()->map('ID', 'Title'))->setEmptyString('- 选一个 -'),
			DropdownField::create('Measurement', '单位', Config::inst()->get('ProductPage', 'Measurements'))->setEmptyString('- 选一个 -'),
			NumericField::create('Weight', '单位重量')->setDescription('Kg')
		), 'URLSegment');


		$fields->addFieldsToTab('Root.Images', array(
			UploadField::create('Poster', '高清大图(横版)')->setFolderName('products'),
			UploadField::create('VPoster', '高清大图(竖版)')->setFolderName('products'),
			UploadField::create('ProductPhotos', '商品预览图')->setFolderName('products'),
			UploadField::create('ProductDescs', '商品介绍图')->setFolderName('products')
		));

		$fields->addFieldsToTab('Root.ResampledImages', array(
			SaltedUploader::create('Square', '方形裁剪')->setFolderName('products')
		));

		if (!empty($this->ID)) {
			$fields->addFieldsToTab('Root.Groupons', array(
				Grid::make('Groupons', '特价', $this->Groupons(), false)
			));
			$fields->addFieldsToTab('Root.Variants', array(
				Grid::make('Variants', '品种', $this->Variants(), false)
			));
		}

		//pricing
		$onflyfield = array(
			'Cost'  => array(
				'title' => 'Cost',
				'field' => 'TextField'
			),
			'Price'  => array(
				'title' => 'Price',
				'field' => 'TextField'
			)
		);

		if (!empty($this->ID)) {
			$fields->addFieldsToTab(
				'Root.Pricing',
				array(
					Grid::makeEditable('Pricings', 'Pricings', $this->Pricings()->sort('ID', 'DESC'),false, $onflyfield)
				)
			);

			$fields->addFieldToTab(
				'Root.Watchers',
				Grid::make('Watches', '吃瓜群众', $this->Watches()->sort('ID', 'DESC'),false)
			);

			$fields->fieldByName('Root.Groupons')->setTitle('特价');
			$fields->fieldByName('Root.Variants')->setTitle('品种');
			$fields->fieldByName('Root.Pricing')->setTitle('价格');
			$fields->fieldByName('Root.Watchers')->setTitle('吃瓜群众');
			$fields->addFieldToTab('Root.Main', $tags);

		}

		$fields->fieldByName('Root.Images')->setTitle('商品图片');
		$fields->fieldByName('Root.ResampledImages')->setTitle('裁剪图片');
		return $fields;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$parent = ProductLandingPage::get();
		if ($parent->count() > 0) {
			$this->ParentID = $parent->first()->ID;
		}

		$this->ShowInMenus = false;
	}

	public function PricingData() {
		return $this->Pricings()->format(array(
			'pricing_id'	=>	'ID',
			'cost'			=>	'Cost',
			'price'			=>	'Price',
			'created'		=>	'Created'
		));
	}

	public function VariantData() {
		return $this->Variants()->format(array(
			'variant_id'	=>	'ID',
			'title'			=>	'Title',
			'pricings'		=>	'PricingData'
		));
	}

	public function getCurrentGroupon() {
		$groupons = $this->Groupons();
		$excludes = array();
		foreach ($groupons as $groupon) {
			if ($groupon->Finished()) {
				$excludes[] = $groupon->ID;
			}
		}

		$filtered = $groupons->exclude(array('ID' => $excludes));
		return $filtered->count() > 0 ? $filtered->first() : null;
	}

	public function getSquared() {

		if (!empty($this->SquareID)) {
			return $this->Square()->Cropped();
		}

		return $this->Poster();
	}

	public function getVertical() {
		if (!empty($this->VPosterID)) {
			return $this->VPoster()->Cropped();
		}

		return $this->Poster();
	}

	public function MiniOrderForm() {
		return new MiniOrderForm($this);
	}

	public function isFav() {
		if ($member = Member::currentUser()) {
			$fav = $member->Favourites()->filter(array('ProductID' => $this->ID));
			return $fav->count() > 0 ? true : false;
		}

		$fav = Favourite::get()->filter(array('Session' => session_id(), 'ProductID' => $this->ID));

		return $fav->count() > 0 ? true : false;

	}

	public function SubscribeForm($label, $on = 'WatchPromo') {
		return new SubscribeForm($this, $label, array('watch_on' => $on), $this->ID);
	}
}

class ProductPage_Controller extends Page_Controller {

	protected static $allowed_actions = array(
		'ProductOrderForm'
	);

	public function ProductOrderForm() {
		return new ProductOrderForm($this);
	}

	public function getPrice() {
		if ($this->Pricings()->count() > 0) {
			return $this->Pricings()->first()->Price;
		}

		return '$0.00';
	}

	public function getNumPaid() {
		if ($groupon = $this->getCurrentGroupon()) {

			$member_id = Member::currentUser() ? Member::currentUserID() : session_id();

			return Utils::NumPaid($groupon->ID, $member_id);
		}

		return 0;
	}

	public function getNumUnpaid() {
		if ($groupon = $this->getCurrentGroupon()) {
			$member_id = Member::currentUser() ? Member::currentUserID() : session_id();

			return Utils::NumUnpaid($groupon->ID, $member_id);
		}

		return 0;
	}
}
