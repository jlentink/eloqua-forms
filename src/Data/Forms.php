<?php

namespace EloquaForms\Data;


use EloquaForms\Client;

class Forms
{

    const API_ENDPOINT = '/api/REST/2.0/assets/forms';

    /**
     * The client to connect to Eloqua to.
     * @var Client
     */
    protected $_client;

    /**
     * Forms constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * Get all forms from Eloqua
     * @return Form[]
     */
    public function get(){
        $formsArray = [];
        $formsResponse = $this->_client->performRequest(self::API_ENDPOINT, null, Client::HTTP_GET);
        $forms = json_decode($formsResponse->getBody()->getContents());
        if(isset($forms->elements) && is_array($forms->elements)){
            foreach($forms->elements as $formObject){
                $currentForm = new Form($this->_client);
                $currentForm->setFormId($formObject->id);
                $currentForm->setName($formObject->name);
                $formsArray[] = $currentForm;
            }
        }
        return $formsArray;
    }
}