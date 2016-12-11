<?php
/**
 * @file FreightOptionAdmin.php
 *
 * Left-hand-side tab : Admin orders
 * */

class FreightOptionAdmin extends ModelAdmin {
	private static $managed_models = array('FreightOption');
	private static $url_segment = 'freight-options';
	private static $menu_title = 'Freights';
	private static $menu_icon = 'mainsite/images/freight-truck.png';
	
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