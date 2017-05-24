<?php
/*
 * @file ExamplePurger.php
 *
 * Purge dummy content from the DB.
 */

class StoreOrderFixer extends BuildTask {
	protected $title = 'StoreOrder Fixer';
	protected $description = 'Fix store orders';

	protected $enabled = true;

	public function run($request)
    {
		if (!Member::currentUser()) {
			return Controller::curr()->httpError(403, 'Access denied');
		}

		if (!Member::currentUser()->inGroup('Administrators')) {
			return Controller::curr()->redirect('/dev/tasks');
		}

        $StoreOrders = StoreOrder::get();
        foreach ($StoreOrders as $StoreOrder)
        {
            $StoreOrder->MemberID = 10;
            $StoreOrder->write();
        }

	}


}
