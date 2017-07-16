<?php

use SaltedHerring\Debugger;

class StoreOrderForm extends Form
{
    public function __construct($controller) {

		$fields = new FieldList();
		//$fields->push(TextField::create('Lookup', 'Lookup')->setAttribute('autocomplete', 'off'));

		$actions = new FieldList(
            FormAction::create('byCash','CASH'),
            // FormAction::create('byCredit','Credit'),
    		FormAction::create('byEFTPOS','EFTPOS')
		);

		// $required_fields = array(
		// 	'Lookup'
		// );

		// $required = new RequiredFields($required_fields);

		parent::__construct($controller, 'StoreOrderForm', $fields, $actions);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'storist/v1/store', "StoreOrderForm"));
	}

    public function ProcessOrder($data, $method)
    {
        if (!empty($data['list'])) {
            $list = $data['list'];
            $order = new StoreOrder();
            $order->PaymentMethod = $method;
            $order->write();

            foreach ($list as $item)
            {
                $orderItem = new StoreOrderItem();
                $orderItem->Quantity = $item['Quantity'];
                $orderItem->MCProdID = $item['MCProdID'];
                $orderItem->AltAmount->Currency = Config::inst()->get('SaltedPayment', 'DefaultCurrency');
                $orderItem->AltAmount->Amount = $item['AltAmount'];
                $orderItem->StoreOrderID = $order->ID;
                $orderItem->write();
            }

            $order->Sold = true;
            $order->write();

            return json_encode(
                        array(
                            'result'    =>  true,
                            'receipt'   =>  $order->Title,
                            'method'    =>  $method,
                            'when'      =>  $order->Created
                        )
                    );
        }

        return json_encode(array('result' => false));
    }

    public function byCash($data, $form)
    {
        if ($this->controller->request->isAjax()) {
            if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
                return $this->ProcessOrder($data, 'Cash');
            }
            return $this->controller->redirectBack();
        }

        return $this->controller->httpError(401, 'nope');
    }

    public function byCredit($data, $form)
    {
        Debugger::inspect('credit');
        if ($this->controller->request->isAjax()) {
            if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {

            }

            return $this->controller->redirectBack();
        }

        return $this->controller->httpError(401, 'nope');
    }

    public function byEFTPOS($data, $form)
    {
        if ($this->controller->request->isAjax()) {
            if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
                return $this->ProcessOrder($data, 'EFTPOS');
            }

            return $this->controller->redirectBack();
        }

        return $this->controller->httpError(401, 'nope');
    }
}
