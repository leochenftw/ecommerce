<?php use SaltedHerring\Debugger as Debugger;

class Groupon extends DataObject {
	protected static $db = array(
		'ActivityID'	=>	'Int',
		'Title'			=>	'Varchar(256)',
		'Type'			=>	'Enum("限量抢购,限时抢购,限时限量")',
		'Content'		=>	'HTMLText',
		'Start'			=>	'SS_Datetime',
		'StockCount'	=>	'Int',
		'End'			=>	'SS_Datetime'
	);

	protected static $default_sort = array(
		'ID'			=>	'DESC'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('ActivityID');
		if (empty($this->ID)) {
			$fields->removeFieldsFromTab('Root.Main', array(
				'Title',
				'StockCount',
				'End'
			));
		} else {
            $fields->removeFieldsFromTab('Root.Main', array(
                'ProductID'
            ));
			$fields->addFieldToTab('Root.Main', ReadonlyField::create('Title', '团购活动'), 'Type');
            $fields->addFieldToTab(
                'Root.Main',
                DropdownField::create(
                    'ProductID',
                    'Product',
                    Versioned::get_by_stage('ProductPage', 'Stage')->map('ID', 'Title')
                )->setEmptyString('- select one -')
            );
		}

		if (!empty($this->Type)) {
			if ($this->Type == '限量抢购') {
				$fields->removeFieldsFromTab('Root.Main', array(
					'End'
				));
				$stock = $fields->fieldByName('Root.Main.StockCount');
				$stock->setTitle('库存');
			} elseif ($this->Type == '限时抢购') {
				$fields->removeFieldsFromTab('Root.Main', array(
					'StockCount'
				));
			} else {
				$stock = $fields->fieldByName('Root.Main.StockCount');
				$stock->setTitle('库存');
			}
		}

		return $fields;
	}

	protected static $has_one = array(
		'Product'	=>	'ProductPage'
	);

	protected static $has_many = array(
		'OrderItems'		=>	'OrderItem'
	);

	public function Started() {
		if (!empty($this->Start)) {
			if (strtotime($this->Start) <= time()) {
				return true;
			}
		}
		return false;
	}

	public function Finished() {
		if ($this->Type == '限量抢购') {
			//Debugger::inspect($this->ID);
			if (!empty($this->StockCount)) {
				return false;
			}
		} elseif ($this->Type == '限时抢购') {
			if (!empty($this->End)) {
				if (strtotime($this->End) > time()) {
					return false;
				}
			}
		} else {
			if (!empty($this->End)) {
				if (strtotime($this->End) > time() && !empty($this->StockCount)) {
					return false;
				}
			}
		}

		return true;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (empty($this->ActivityID) && !empty($this->ProductID)) {
			$this->ActivityID = $this->Product()->Groupons()->count() + 1;
		}

		if (!empty($this->ActivityID)) {
			$this->Title = $this->Product()->Title . ' - No.' . $this->ActivityID . ' Group-on';
		}
	}

	public function getTilStart($as_data = false) {
		$remain = strtotime($this->Start) - time();
		return $this->formatSeconds($remain, true, $as_data);
	}

	public function getRemaining() {
		if ($this->Type == '限时抢购') {
			$remain = strtotime($this->End) - time();
			return $this->formatSeconds($remain);
		}

		if ($this->Type == '限时限量') {
			return new ArrayData(array(
					'time'	=>	strtotime($this->End) - time(),
					'stock'	=>	$this->StockCount
				));
		}

		return $this->StockCount;
	}

	private function formatSeconds( $seconds, $show_day = false, $as_data = false)
	{
	  $days = 0;
	  $hours = 0;
	  $milliseconds = str_replace( "0.", '', $seconds - floor( $seconds ) );

	  if ( $seconds > 3600 )
	  {
		$hours = floor( $seconds / 3600 );
	  }

	  $minutes = floor(($seconds % 3600) / 60);

	  $seconds = $seconds % 3600;
	  $sec = $seconds % 60;

	  if ($show_day) {
		$days = floor( $hours / 24);
		$hours = $hours % 24;
	  }

	  if ($as_data) {
		  return new ArrayData(array(
		  			'Days'		=>	str_pad( $days, 2, '0', STR_PAD_LEFT ),
					'Hours'		=>	str_pad( $hours, 2, '0', STR_PAD_LEFT ),
					'Minutes'	=>	str_pad( $minutes, 2, '0', STR_PAD_LEFT ),
					'Seconds'	=>	str_pad( $sec, 2, '0', STR_PAD_LEFT )
		  		));
	  }

	  return ($show_day ? (str_pad( $days, 2, '0', STR_PAD_LEFT ) . ' day' . ($days > 1 ? 's ' : ' ')) : '')
	  	   . str_pad( $hours, 2, '0', STR_PAD_LEFT )
		   . gmdate( ':i:s', $seconds )
		   . ($milliseconds ? ".$milliseconds" : '')
	  ;
	}
}
