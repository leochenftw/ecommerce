<?php

use SaltedHerring\Debugger;
use GuzzleHttp\Client;

class StoreLookupForm extends Form
{
    public function __construct($controller) {

		$fields = new FieldList();
		$fields->push(TextField::create('Lookup', 'Lookup')->setAttribute('autocomplete', 'off'));

		$actions = new FieldList(
			$btnSubmit = FormAction::create('doLookup','Look up')
		);

		$required_fields = array(
			'Lookup'
		);

		$required = new RequiredFields($required_fields);

		parent::__construct($controller, 'StoreLookupForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, 'storist/v1/store', "StoreLookupForm"));
	}

    public function doLookup($data, $form)
    {
        if ($this->controller->request->isAjax()) {
            if (!empty($data['SecurityID']) && $data['SecurityID'] == Session::get('SecurityID')) {
                if ($lookup = $data['Lookup']) {
                    $client = new Client([
                        'base_uri' => 'https://merchantcloud.leochen.co.nz/'
                    ]);

                    $response = $client->request(
                        'GET',
                        'products',
                        array(
                            'query' => ['barcode' => $lookup]
                        )
                    );

                    // $filter = array(
                    //     'Barcode'               =>  $lookup,
                    //     'Title:PartialMatch'    =>  $lookup,
                    //     'Alias:PartialMatch'    =>  $lookup
                    // );
                    //
                    // $result = Versioned::get_by_stage('ProductPage', 'Stage')->filterAny($filter)->limit(5)->format(array(
                    //                         'id'        =>  'ID',
                    //                         'title'     =>  'Title',
                    //                         'alias'     =>  'Alias',
                    //                         'price'     =>  'Price'
                    //                     ));
                    return json_encode(json_decode($response->getBody()));
                }

            }

            return $this->controller->redirectBack();
        }

        return $this->controller->httpError(401, 'nope');
    }
}
