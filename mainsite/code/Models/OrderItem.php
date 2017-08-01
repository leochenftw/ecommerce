<?php use SaltedHerring\Debugger as Debugger;

class OrderItem extends DataObject {

	protected static $db = array(
        'UnitPrice'         =>  'Decimal',
		'Quantity'			=>	'Int'
	);

    /**
     * Define the default values for all the $db fields
     * @var array
     */
    private static $defaults = array(
        'Quantity'			=>	1
    );

	protected static $summary_fields = array(
		'Customer',
		'Quantity'
	);

	protected static $has_one = array(
		'Groupon'			=>	'Groupon',
        'Product'           =>  'ProductPage',
		'Order'				=>	'Order'
	);

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (!empty($this->ProductID)) {
            $this->UnitPrice    =   $this->Product()->Price;
        }
    }

    /**
     * Event handler called after writing to the database.
     */
    public function onAfterWrite()
    {
        parent::onAfterWrite();
        if ($this->Order()->exists()) {
            $this->Order()->write();
        }
    }

	public function Customer() {
		return !empty($this->Order()->CustomerID) ? $this->Order()->Customer()->Email : $this->Order()->Session;
	}

	public function getSubtotal($format_output = false) {
		$sum = $this->UnitPrice * $this->Quantity;
		if (!$format_output) {
			return $sum;
		}
		return number_format($sum, 2, '.', ',');
	}

	public function FormattedSubtotal() {
		return $this->getSubtotal(true);
	}

    public function getProductTitle()
    {
        $prod = !empty($this->GrouponID) ? $this->Groupon() : $this->Product();
        return Controller::curr()->getLanguage() == 'Chinese' ? $prod->Chinese : $prod->Title;
    }

    public function getPoster()
    {
        if (!empty($this->GrouponID)) {
            return $this->Groupon()->Product()->Poster();
        }

        if (!empty($this->ProductID)) {
            return $this->Product()->Poster();
        }

        return null;
    }

    public function getItemLink()
    {
        $prod = !empty($this->GrouponID) ? $this->Groupon()->Product() : $this->Product();
        return $prod->Link();
    }
}
