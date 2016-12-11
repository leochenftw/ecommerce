<?php use SaltedHerring\Debugger as Debugger;

class ContactPage extends Page {
	
}

class ContactPage_Controller extends Page_Controller {
	protected static $allowed_actions = array(
		'ContactForm'
	);
	
	public function ContactForm() {
		return new ContactForm($this);
	}
}