<?php

class PaymentMethodAdmin extends ModelAdmin {
	private static $managed_models = array('PaymentMethod');
	private static $url_segment = 'payment-methods';
	private static $menu_title = 'Payment Method';
	private static $menu_icon = 'mainsite/images/credit-card.png';
	
	public function getEditForm($id = null, $fields = null) {
		
		$form = parent::getEditForm($id, $fields);
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldPrintButton')
			->addComponents(
				new GridFieldPaginatorWithShowAll(30)
			);
		return $form;
	}
}