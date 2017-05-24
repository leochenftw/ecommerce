<?php
use SaltedHerring\Grid;
class CustomSiteConfig extends DataExtension {

	public static $db = array(
        'GoogleSiteVerificationCode'    =>  'Varchar(128)',
        'GoogleAnalyticsCode'           =>  'Varchar(20)',
        'SiteVersion'                   =>  'Varchar(10)',
        'GoogleCustomCode'              =>  'HTMLText'
	);

    public static $has_many = array(
        'ExchangeRates'                 =>   'Pricing'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.Google", new TextField('GoogleSiteVerificationCode', 'Google Site Verification Code'));
		$fields->addFieldToTab("Root.Google", new TextField('GoogleAnalyticsCode', 'Google Analytics Code'));
		$fields->addFieldToTab("Root.Google", new TextareaField('GoogleCustomCode', 'Custom Google Code'));
        $fields->addFieldToTab('Root.ExchangeRates', $grid = Grid::make('ExchangeRates', 'Exchange Rates', $this->owner->ExchangeRates()->sort(array('ID' => 'DESC')), false));
        $config = $grid->getConfig();
        $config->removeComponentsByType('GridFieldAddNewButton');
		$fields->addFieldToTab('Root.Main', new TextField('SiteVersion', 'Site Version'));
	}

    public function getRate() {
		$rates = $this->owner->ExchangeRates()->sort(array('ID' => 'DESC'));
		if ($rates->count() > 0) {
			return $rates->first();
		}
		return 1;
	}

}
