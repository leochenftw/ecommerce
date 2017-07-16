<?php use SaltedHerring\Debugger as Debugger;
/**
 * @file ProductAdmin.php
 *
 * Left-hand-side tab : Admin orders
 * */

class ProductAdmin extends ModelAdmin {
	private static $managed_models = array('ProductPage');
	private static $url_segment = 'products';
	private static $menu_title = 'Products';
	private static $menu_icon = 'mainsite/images/shopping-cart.png';

	public function getEditForm($id = null, $fields = null) {

		$form = parent::getEditForm($id, $fields);
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldPrintButton')
			->removeComponentsByType('GridFieldDetailForm')
			->addComponents(
				new VersionedForm(),
				new GridFieldPaginatorWithShowAll(30)
			);
		return $form;
	}

	public function getList() {
		$list = Versioned::get_by_stage('ProductPage', 'Stage');

		return $list;
    }
}
