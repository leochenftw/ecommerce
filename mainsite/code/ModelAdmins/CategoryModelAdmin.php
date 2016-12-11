<?php
/**
 * @file CategoryModelAdmin.php
 *
 * Left-hand-side tab : Admin customers
 * */

class CategoryModelAdmin extends ModelAdmin {
	private static $managed_models = array('Category');
	private static $url_segment = 'categories';
	private static $menu_title = 'Categories';
	private static $menu_icon = 'mainsite/images/network.png';
	
	public function getEditForm($id = null, $fields = null) {
		
		$form = parent::getEditForm($id, $fields);
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldPrintButton')
			->addComponents(
				new GridFieldPaginatorWithShowAll(30),
				new GridFieldOrderableRows('SortOrder')
			);
		return $form;
	}
}