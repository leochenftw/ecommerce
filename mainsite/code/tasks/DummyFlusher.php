<?php
/*
 * @file ExamplePurger.php
 *
 * Purge dummy content from the DB.
 */

class DummyFlusher extends BuildTask {
	protected $title = 'Dummy Flusher';
	protected $description = 'Flush dummies';

	protected $enabled = false;

	public function run($request)
    {
		if (!Member::currentUser()) {
			return Controller::curr()->httpError(403, 'Access denied');
		}

		if (!Member::currentUser()->inGroup('Administrators')) {
			return Controller::curr()->redirect('/dev/tasks');
		}

        $stage = Versioned::get_by_stage('ProductPage', 'Stage');
        $live = Versioned::get_by_stage('ProductPage', 'Live');

        foreach ($live as $liveitem) {
            $liveitem->deleteFromStage('Live');
        }

        foreach ($stage as $stageitem) {
            $stageitem->deleteFromStage('Stage');
        }

	}


}
