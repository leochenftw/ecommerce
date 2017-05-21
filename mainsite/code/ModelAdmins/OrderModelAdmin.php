<?php
/**
 * @file OrderModelAdmin.php
 *
 * Left-hand-side tab : Admin orders
 * */

class OrderModelAdmin extends ModelAdmin {
	private static $managed_models = array('Order','StoreOrder');
	private static $url_segment = 'orders';
	private static $menu_title = 'Orders';
	private static $menu_icon = 'mainsite/images/shopping-bag.png';

	public function getEditForm($id = null, $fields = null) {

		$form = parent::getEditForm($id, $fields);
		//SaltedHerring\Debugger::inspect($this->modelClass);

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
