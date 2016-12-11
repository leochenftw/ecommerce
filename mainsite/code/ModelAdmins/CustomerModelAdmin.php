<?php
/**
 * @file CustomerModelAdmin.php
 *
 * Left-hand-side tab : Admin customers
 * */

class CustomerModelAdmin extends ModelAdmin {
	private static $managed_models = array('Customer');
	private static $url_segment = 'customers';
	private static $menu_title = 'Customers';
	private static $menu_icon = 'mainsite/images/customers.png';
	
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