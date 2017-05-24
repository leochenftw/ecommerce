<?php
use SaltedHerring\Debugger;
use SaltedHerring\Currency;
class Exchanger extends BuildTask {
	protected $title = 'Exchanger';
	protected $description = 'Get the exchange rate and store on the server';

	protected $enabled = true;

	public function run($request) {
		$siteconfig = SiteConfig::current_site_config();
		$rate = Currency::exchange(1);
		$pricing = new Pricing();
		$pricing->Cost = $rate;
		$pricing->Price = $rate + 0.25;
		$pricing->write();
		$siteconfig->ExchangeRates()->add($pricing);
	}
}
