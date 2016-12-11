<?php

use SaltedHerring\Debugger as Debugger;

class VersionedForm extends GridFieldDetailForm {
	
}

class VersionedForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
	private static $allowed_actions = array(
		'edit',
		'view',
		'ItemEditForm',
		'doPublish',
		'doUnpublish'
	);
	
	public function ItemEditForm() {
		$form = parent::ItemEditForm();
		
		if ($form instanceof Form) {
			$actions = $form->Actions();
			$record = $this->getRecord();
			//$actions->removeByName('action_doSave');
			//$actions->unshift($btnSave = FormAction::create('SaveProdcut', 'Save'));
			$actions->insertBefore('action_doDelete', $btnPublish = FormAction::create('doPublish','Save &amp; Publish'));
			//$btnSave->addExtraClass('ss-ui-action-constructive');
			$btnPublish->addExtraClass('ss-ui-action-constructive');
			if (!empty($record->ID)) {
				if ($record->isPublished()) {
					$actions->removeByName('action_doDelete');
					$actions->push(FormAction::create('doUnpublish', 'Unpublish')->addExtraClass('ss-ui-action-destructive'));
				}
			}
			
			$form->setActions($actions);
		}
		
		return $form;
	}
	
	public function doSave($data, $form) {
		$new_record = $this->record->ID == 0;
		$controller = $this->getToplevelController();
		$list = $this->gridField->getList();
		
		if(!$this->record->canEdit()) {
			return $controller->httpError(403);
		}
		 
		if (isset($data['ClassName']) && $data['ClassName'] != $this->record->ClassName) {
			$newClassName = $data['ClassName'];
			// The records originally saved attribute was overwritten by $form->saveInto($record) before.
			// This is necessary for newClassInstance() to work as expected, and trigger change detection
			// on the ClassName attribute
			$this->record->setClassName($this->record->ClassName);
			// Replace $record with a new instance
			$this->record = $this->record->newClassInstance($newClassName);
		}
		
		try {
			$form->saveInto($this->record);
			$this->record->write();
			$this->record->writeToStage('Stage');
			$extraData = $this->getExtraSavedData($this->record, $list);
			$list->add($this->record, $extraData);
		} catch(ValidationException $e) {
			$form->sessionMessage($e->getResult()->message(), 'bad', false);
			if ($controller->getRequest()->isAjax()) {
				$responseNegotiator = new PjaxResponseNegotiator(array(
					'CurrentForm' => function () use (&$form) {
						return $form->forTemplate();
					},
				));
				$controller->getRequest()->addHeader('X-Pjax', 'CurrentForm');
				return $responseNegotiator->respond($controller->getRequest());
			}
			Session::set("FormInfo.{$form->FormName()}.errors", array());
			Session::set("FormInfo.{$form->FormName()}.data", $form->getData());
			return $controller->redirectBack();
		}
		
		// TODO Save this item into the given relationship
		
		$link = '<a href="' . $this->Link('edit') . '">"' 
			. htmlspecialchars($this->record->Title, ENT_QUOTES) 
			. '"</a>';
		$message = _t(
			'GridFieldDetailForm.Saved',
			'Saved {name} {link}',
			array(
				'name' => $this->record->i18n_singular_name(),
				'link' => $link
			)
		);
		
		$form->sessionMessage($message, 'good', false);
		
		if($new_record) {
			return $controller->redirect($this->Link());
		} elseif($this->gridField->getList()->byId($this->record->ID)) {
			// Return new view, as we can't do a "virtual redirect" via the CMS Ajax
			// to the same URL (it assumes that its content is already current, and doesn't reload)
			return $this->edit($controller->getRequest());
		} else {
			// Changes to the record properties might've excluded the record from
			// a filtered list, so return back to the main view if it can't be found
			$noActionURL = $controller->removeAction($data['url']);
			$controller->getRequest()->addHeader('X-Pjax', 'Content');
			return $controller->redirect($noActionURL, 302);
		}
	}
	
	public function doUnpublish($data, $form) {
		$this->getRecord()->deleteFromStage('Live');
		$controller = Controller::curr();
		return $this->edit($controller->getRequest());
	}
	
	public function doPublish($data, $form) {
		$this->doSave($data, $form);
		$this->getRecord()->doPublish();
		$controller = Controller::curr();
		return $this->edit($controller->getRequest());
	}
}