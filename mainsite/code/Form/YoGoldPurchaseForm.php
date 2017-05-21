<?php use SaltedHerring\Debugger as Debugger;

class YoGoldPurchaseForm extends Form {
    public function __construct($controller) {
        $member = Member::currentUser();
        $fields = new FieldList();
        $fields->push(LiteralField::create('CurrentYogold', '<div class="current-balance">当前余额 <span>' . $member->Credit . '</span></div>'));
        $fields->push($amount = TextField::create('YoGoldAmount', '请输入充值金额')->setAttribute('placeholder', '100.00'));
        $fields->push(OptionsetField::create('PaymentMethod', '支付方式', PaymentMethod::get()->filter(array('Title:not' => array('银行转账', '优Gold')))->map('ID', 'Title')));

        $actions = new FieldList(
            $btnSubmit = FormAction::create('doPurchase','充值')
        );

        $required_fields = array(
            'YoGoldAmount',
            'PaymentMethod'
        );

        $required = new RequiredFields($required_fields);

        parent::__construct($controller, 'YoGoldPurchaseForm', $fields, $actions, $required);
        $this->setFormMethod('POST', true)
             ->setFormAction(Controller::join_links(BASE_URL, 'member', "YoGoldPurchaseForm"));

    }

    public function doPurchase($data, $form) {
        if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
            $amount = (float) $data['YoGoldAmount'];
            if ($amount > 0) {
                $order = Utils::getTopupOrder();
                $order->Amount->Amount = $amount;
                $order->write();
                $payment_method = PaymentMethod::get()->byID($data['PaymentMethod']);
                return $order->Pay($payment_method->Title);
            }
            $form->addErrorMessage('YoGoldAmount', '老板，您充' . $amount . '块，我们找不开啊……', "bad");
            return $this->controller->redirectBack();
        }

        return Controller::curr()->httpError(400, 'fuck off');

    }
}
