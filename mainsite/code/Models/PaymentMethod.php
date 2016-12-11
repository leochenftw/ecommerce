<?php use SaltedHerring\Debugger as Debugger;

class PaymentMethod extends DataObject {
	protected static $db = array(
		'Title'		=>	'Varchar(16)',
		'Template'	=>	'Varchar(128)'
	);
	
	protected static $extensions = array(
		'ApisedExt'
	);
	
	public function forTemplate() {
		if (!empty($this->Template)) {
			$credit = Member::currentUser() ? Member::currentUser()->Credit : 0;
			$amount_due = Utils::getCurrentCart(Member::currentUser() ? Member::currentUserID() : session_id())->AmountDue;
			return $this->customise(array(
					'Credit'		=>	Member::currentUser() ? number_format(Member::currentUser()->Credit, 2, '.', ',') : 0,
					'Ref'			=>	Utils::getReference(Member::currentUser() ? Member::currentUserID() : session_id()),
					'MessageType'	=>	$this->MessageTypeDefinder(),
					'Balance'		=>	number_format(($credit - $amount_due), 2, '.', ',')
				))->renderWith($this->Template);
		}
		
		return false;
	}
	
	private function MessageTypeDefinder() {
		if (empty(Member::currentUser())) {
			return -1;
		}
		
		$credit = Member::currentUser()->Credit;
		$amount_due = Utils::getCurrentCart(Member::currentUser() ? Member::currentUserID() : session_id())->AmountDue;
		
		if ($credit < $amount_due) {
			return 1;
		}
		
		return 0;
	}
}