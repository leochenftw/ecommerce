<?php

use SaltedHerring\Debugger;

class SupplierEditForm extends Form {

    public function __construct($controller) {
        $product    =   $controller->data();
        $member     =   Member::currentUser();
        $fields = new FieldList(
            TextField::create('FirstName', 'First name')->setValue($member->FirstName),
            TextField::create('Surname', 'Last name')->setValue($member->Surname),
            TextField::create('Email', 'Email address')->setValue($member->Email),
            $password = ConfirmedPasswordField::create('Password')->setChildrenTitles(array('','')),
            TextField::create('Phone', 'Your contact number')->setValue($member->Phone),
            $logo = UploadField::create('Logo', 'Business logo'),
            TextField::create('TradingName', 'Trading name')->setValue($member->TradingName),
            TextField::create('NZLocation', 'Trading location')->setValue($member->NZLocation),
            TextField::create('GST', 'GST number')->setValue($member->GST)->setDescription('format: xxx-xxx-xxx'),
            TextField::create('ContactNumber', 'Business contact number')->setValue($member->ContactNumber)
        );

        $password_children = $password->getChildren();
        $password_children->fieldByName('Password[_Password]')->setAttribute('placeholder', 'Password');
        $password_children->fieldByName('Password[_ConfirmPassword]')->setAttribute('placeholder', 'Confirm password');

        $logo->setFolderName('members/' . Member::CurrentUserID() . '/logos')
                ->setCanAttachExisting(false)
                // ->setAllowedMaxFileNumber(10)
                ->setAllowedExtensions(array('jpg', 'jpeg', 'png'))
                ->setPreviewMaxWidth(400)
                ->setPreviewMaxHeight(400)
                ->setCanPreviewFolder(false)
                ->setAutoUpload(false)
                ->setFieldHolderTemplate('LogoUploadField')
                ->addExtraClass('logo-uploader');


        $actions = new FieldList(
            $btnSubmit = FormAction::create('doSave','Save')->addExtraClass('is-outlined is-info')
        );

        $required_fields = array(
            'FirstName',
            'Surname',
            'Email',
            'TradingName',
            'GST',
            'ContactNumber'
        );

        $required = new RequiredFields($required_fields);

        parent::__construct($controller, 'SupplierEditForm', $fields, $actions, $required);
        $this->setFormMethod('POST', true)
             ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), 'account', "SupplierEditForm"));
    }

    public function validate()
    {
        Session::clear('FormInfo');
        $data           =   $this->controller->request->postVars();
        $password       =   $data['Password']['_Password'];
        $re_password    =   $data['Password']['_ConfirmPassword'];

        $errors         =   0;

        if ($password != $re_password) {
            $this->addErrorMessage('Password', 'Passwords do not match', 'is-danger');
            $errors++;
        }

        if ($member = Member::currentUser()) {
            if ($member->Email != $data['Email']) {
                if (Member::get()->filter(array('Email' => $data['Email']))->count() > 0) {
                    $this->addErrorMessage('Email', 'Email address is used by other member', 'is-danger');
                    $errors++;
                }
            }
        }

        if ($errors > 0) {
            Session::set("FormInfo.{$this->FormName()}.data", $data);
            return false;
        }

        return true;
    }

    public function doSave($data, $form) {
        Session::clear('Message');
        if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {

            if ($member = Member::currentUser()) {

                if ($member->ClassName == 'Supplier') {
                    $FormData               =   $form->getData();

                    $member->TradingName    =   $data['TradingName'];
                    $member->NZLocation     =   $data['NZLocation'];
                    $member->GST            =   $data['GST'];
                    $member->ContactNumber  =   $data['ContactNumber'];
                    $member->FirstName      =   $data['FirstName'];
                    $member->Surname        =   $data['Surname'];
                    $member->Email          =   $data['Email'];
                    $member->Phone          =   $data['Phone'];

                    $password               =   $data['Password']['_Password'];

                    if (!empty($password)) {
                        $member->changePassword($password);
                    }

                    if (!empty($FormData['Logo'])) {
                        $member->LogoID     =   $FormData['Logo']['Files'][0];
                    }

                    $member->write();
                    Session::clear('FormInfo');
                }
            }

            return Controller::curr()->redirectBack();
        }

        return Controller::curr()->httpError(400, 'fuck off');
    }

    public function isBadPassword()
    {
        if ($message = Session::get("FormInfo.{$this->FormName()}.errors")) {
            foreach ($message as $segment)
            {
                if ($segment['fieldName'] == 'Password') {
                    return $segment['messageType'];
                }
            }
        }
        return false;
    }

}
