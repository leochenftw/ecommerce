<?php use SaltedHerring\Debugger as Debugger;

class MemberExt extends DataExtension {
	protected static $db = array(
        'Language'      =>  'Varchar(16)',
		'Phone'			=>	'Varchar(48)',
		'Subscribe'		=>	'Boolean'
	);

	protected static $has_many = array(
		'Address'		=>	'Address',
		'Favourites'	=>	'Favourite',
		'Watchlist'		=>	'Watch.Watcher'
	);

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'Language',
                'Language preference',
                array(
                    'en_NZ'     =>  'English',
                    'zh_Hans'   =>  '简体中文'
                )
            )
        );
    }

	public function isEnglish() {
		return ctype_alpha($this->owner->FirstName) && ctype_alpha($this->owner->Surname);
	}

	public function isWatchingProduct($prod_id) {
		$lst = $this->owner->Watchlist();
		foreach ($lst as $item) {
			if ($item->WatchingID == $prod_id) {
				return true;
			}
		}

		return false;
	}
}
