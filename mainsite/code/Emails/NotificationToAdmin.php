<?php

class NotificationToAdmin extends Email {

	public function __construct($member, $example = null, $avatar = null) {
		$from    = Email::getAdminEmail();
		$to      = $member->inGroup('children')?$member->ParentEmail:$member->Email;
		$subject = $this->titleMaker($member, $example, $avatar);
		parent::__construct($from, $to, $subject);
		
		$this->setTemplate('Notification');
		
		$this->populateTemplate(new ArrayData(
			 	array (
					'baseURL'		=>	Director::absoluteURL(Director::baseURL()),
					'Sitename'		=>	SiteConfig::current_site_config()->Title,
					'EmailType'		=>	'InLoop',
					'Child'			=>	$member->FirstName,
					'Regarding'		=>	$this->matterFinder($member, $example, $avatar),
					'Avatar'		=>	$avatar,
					'Example'		=>	$example
				)
			 ));
	}
	
	private function titleMaker($member, $example, $avatar) {
		$matter = $this->matterFinder($member, $example, $avatar);
		if (!is_null($example)) { 
			return $member->FirstName . strip_tags($matter);
		}
		
		if (!is_null($avatar)) { 
			return $member->FirstName . strip_tags($matter);
		}
		return $member->FirstName . strip_tags($matter);
	}
	
	private function matterFinder($member, $example, $avatar) {
		if (!is_null($example)) { 
			return ' has made the submission: <strong>' . $example->Title . '</strong> visible to the public';
		}
		
		if (!is_null($avatar)) { 
			return ' is changing the avatar image';
		}
		
		return '\'s Profile page is now visible to the public';
	}
}