<?php

namespace EloquaForms\Data;


use EloquaForms\Client;
use EloquaForms\ClientException;
use EloquaForms\Exception;

class Form
{
    const API_ENDPOINT = '/api/REST/2.0/data/form/';

    /**
     * The form data fields
     *
     * @var FieldValue[]
     */
    protected $_fields = [];

    /**
     * The form id
     *
     * @var int
     */
    protected $_formId = 0;

    /**
     * The client to connect to Eloqua to.
     * @var Client
     */
    protected $_client;

    /**
     * @var FieldElement[]
     */
    protected $_formFields;

    /**
     * @var string
     */
    protected $_name = "";

    /**
     * Form constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * Set the form id
     *
     * @param integer $formId
     * @return Form
     */
    public function setFormId($formId){
        $this->_formId = $formId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormId()
    {
        return $this->_formId;
    }

    /**
     * Add a data field to Eloqua.
     *
     * @param $fieldId
     * @param $value
     * @return Form
     */
    public function addField($fieldId, $value){
        $field = new FieldValue();
        $field
            ->setId($fieldId)
            ->setValue($value);

        $this->_fields[] = $field;

        return $this;
    }

    /**
     * Add field value based by name
     *
     * @param $name
     * @param $value
     * @return $this
     * @throws Exception
     */
    public function addFieldByName($name, $value){
        if(!count($this->_formFields))
            $this->_formFields = $this->pullFields();

        /** @var FieldValue $field */
        foreach($this->_formFields as $field){
            if($name == $field->getHtmlName()){
                $this->addField($field->getId(), $value);
                return $this;
            }
        }

        throw new Exception('Field "' . $name.'" could not found! Found fields [' . implode(', ', $this->_formFields) . ']');
    }

    /**
     * Pull the fields for the form
     *
     * @return mixed
     * @throws Exception
     */
    public function pull(){
        if($this->_formId == 0)
            throw new Exception('No Form id is set.', 200);

        $form = $this->_client->performRequest('/api/REST/2.0/assets/form/' . $this->_formId, null, Client::HTTP_GET);
        return \GuzzleHttp\json_decode($form->getBody()->getContents());
    }


    /**
     * Validate field for name
     *
     * @param $name
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function validateField($name, $value){
        if(!count($this->_formFields))
            $this->_formFields = $this->pullFields();

        foreach($this->_formFields as $field) {
            if ($name == $field->getHtmlName()) {
                return $field->validate($value);
            }
        }

        throw new Exception('Field "'.$name.'" could not found!');
    }

    /**
     * Pull the fields available for this form.
     *
     * @return FieldValue[]
     */
    public function pullFields(){
        $form = $this->pull();
        $fields = [];

        foreach($form->elements as $element){
            $fieldElement = new FieldElement($element);
            $fields[] = $fieldElement;
        }
        return $fields;
    }

    /**
     * Post the form to Eloqua
     *
     * @throws ClientException
     */
    public function post(){

        if($this->_formId == 0)
            throw new Exception('No Form id is set.', 200);

        if(count($this->_fields) == 0)
            throw new Exception('No fields added to the form.', 210);

        $data = new \stdClass();
        $data->id = $this->_formId;
        $data->fieldValues = $this->_fields;
        $this->_client->performRequest(self::API_ENDPOINT . $this->_formId, $data);
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     * @return Form
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }


}