<?php
/**
 * @file PormoAdmin.php
 *
 * Left-hand-side tab : Discount orders
 * */

class PormoAdmin extends ModelAdmin {
	private static $managed_models = array('Promotional');
	private static $url_segment = 'promotionals';
	private static $menu_title = 'Promotions';
	private static $menu_icon = 'mainsite/images/promote.png';
	
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