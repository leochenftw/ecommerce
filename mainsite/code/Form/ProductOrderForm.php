<?php use SaltedHerring\Debugger as Debugger;

class ProductOrderForm extends Form {

    public function __construct($controller) {
        $product = $controller->data();
        $fields = new FieldList();
        if ($product->Variants()->count() > 0) {
            $fields->push($variant = DropdownField::create('Variant', $product->Variants()->first()->Type, $product->Variants()->map('ID', 'Title')));
            $fields->push(LiteralField::create('PriceJS', '<script> var price_map = ' . json_encode($product->Variants()->map('ID', 'Price')->toArray()) . '</script>'));
            $variant->setTitle('种类');
        }

        $fields->push($quantity = TextField::create('Quantity')->setAttribute('value', 1));

        $quantity->setTitle('数量');

        $actions = new FieldList(
            $btnSubmit = FormAction::create('addToCart','买买买'),
            $btnShortlist = FormAction::create('addToFavourite', '')->addExtraClass('icon fav icon-fav')->setUseButtonTag(true)
        );

        if ($product->isFav()) {
            $btnShortlist->addExtraClass('is-fav');
        }

        $btnSubmit->addExtraClass('button');

        $required_fields = array(
            'Quantity'
        );

        if (!empty($variant)) {
            $required_fields[] = $variant;
        }

        $required = new RequiredFields($required_fields);

        parent::__construct($controller, 'ProductOrderForm', $fields, $actions, $required);
        $this->setFormMethod('POST', true)
             ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "ProductOrderForm"));
    }

    public function addToFavourite($data, $form) {
        if ($data['SecurityID'] == Session::get('SecurityID')) {
            $id = $this->Controller->ID;
            $filter = array('ProductID' => $id);
            if ($memberID = Member::currentUserID()) {
                $filter['CustomerID'] = $memberID;
            } else {
                $filter['Session'] = session_id();
            }

            $favourite = Favourite::get()->filter($filter)->first();

            if (empty($favourite)) {
                $favourite = new Favourite();
                $favourite->ProductID = $id;
                $favourite->write();
            } else {
                $favourite->delete();
            }

            return Controller::curr()->redirectBack();
        }

        return Controller::curr()->httpError(400, 'fuck off');
    }

    public function addToCart($data, $form) {
        if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
            $product_id = $this->controller->ID;
            $cart = Utils::getCurrentCart(Member::currentUserID() ? Member::currentUserID() : session_id());

            if (empty($cart)) {
                $cart = new Order();
                $cart->Session = session_id();
                if ($member = Member::currentUser()) {
                    $cart->CustomerID = $member->ID;
                }

                $cart->write();
            }

            if ($orderitem = $cart->OrderItems()->filter(array('ProductID' => $product_id))->first()) {
                $orderitem->Quantity += (int) $data['Quantity'];
            } else {
                $orderitem = new OrderItem();
                $orderitem->ProductID = $product_id;
                $orderitem->OrderID = $cart->ID;
                $orderitem->Quantity = $data['Quantity'];
            }

            $orderitem->write();
            $cart->write();
            return Controller::curr()->redirectBack();
        }


        return Controller::curr()->httpError(400, 'fuck off');

    }
}
