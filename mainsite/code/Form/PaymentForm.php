<?php
use SaltedHerring\Debugger as Debugger;
use SaltedHerring\SaltedPayment\API\Poli;
class PaymentForm extends Form {

    public function __construct($controller) {

        $fields = new FieldList();

        $fields->push(OptionsetField::create('PaymentMethod', '支付方式', PaymentMethod::get()->map('ID', 'Title')));

        $actions = new FieldList(
            $btnSubmit = FormAction::create('ProcessPayment','付款')->performReadonlyTransformation()
        );

        $required_fields = array(
            'PaymentMethod'
        );

        $required = new RequiredFields($required_fields);

        parent::__construct($controller, 'PaymentForm', $fields, $actions, $required);
        $this->setFormMethod('POST', true)
             ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "PaymentHandler"));
    }

    public function ProcessPayment($data, $form)
    {
        if ($data['SecurityID'] == Session::get('SecurityID')) {

            $payment_method = PaymentMethod::get()->byID($data['PaymentMethod']);
            $member = Member::currentUser();
            $order = Utils::getCurrentCart(!empty($member) ? $member->ID : session_id());
            $amount_due = $order->Amount->Amount;

            $payment = null;

            switch ($payment_method->Title) {
                case '优Gold':
                    $credit = !empty($member) ? $member->Credit : 0;
                    if ($credit < $amount_due) {
                        $this->sessionMessage('优Gold余额不足', 'bad');
                        return Controller::curr()->redirectBack();
                    }
                    // $order->Progress = 2;
                    // $order->write();
                    break;

                default:
                    return $order->Pay($payment_method->Title);
                    break;
            }

            if (!empty($payment)) {
                if (!empty($member)) {
                    $payment->PaidByID = $member->ID;
                }
                $payment->Amount->Amount = $amount_due;
                $payment->OrderID = $order->ID;
                $payment->write();
                return;
            }

            return $this->controller->redirectBack();


            // if ($payment_method->Title == '优Gold') {
            //     if ($credit < $amount_due) {
            //         $this->sessionMessage('优Gold余额不足', 'bad');
            //         return Controller::curr()->redirectBack();
            //     }
            // } elseif ($payment_method->Title == '银行转账') {
            //
            // }
            //


            // $transaction = new Transaction();
            // $transaction->AmountReceived = $amount_due;
            // $transaction->OrderID = $order->ID;
            // $transaction->Reference = !empty($data['reference-number']) ? $data['reference-number'] : null;
            // $transaction->PaymentMethodID = $data['PaymentMethod'];
            // $transaction->write();


        }
    }
}
