<?php

namespace EloquaForms\Data;

use EloquaForms\Client;
use EloquaForms\Data\Validation\Validation;
use EloquaForms\Exception;

class FieldElement
{

    const API_ENDPOINT = '/api/REST/2.0/assets/optionList/%s';
    /**
     * @var null|string
     */
    private $_type = null;

    /**
     * @var null|integer
     */
    private $_id = null;
    /**
     * @var null|string
     */
    private $_name = null;

    /**
     * @var null|string
     */
    private $_style = null;

    /**
     * @var null|integer
     */
    private $_createdFromContactFieldId = null;

    /**
     * @var null|string
     */
    private $_displayType = null;

    /**
     * @var null|string
     */
    private $_dataType = null;

    /**
     * @var string
     */
    private $_htmlName = '';

    /**
     * @var bool
     */
    private $_useGlobalSubscriptionStatus = false;

    /**
     * @var int
     */
    private $_optionListId = 0;

    /**
     * @var FieldOption[]
     */
    private $_options = [];

    /**
     * @var Client
     */
    private $_client;

    /**
     * @var Validation[]
     */
    private $_validations = [];

    /**
     * FieldElement constructor.
     * @param \stdClass $element
     * @param Client $client
     */
    public function __construct(\stdClass $element, Client $client){

        $this->_client = $client;

        if(isset($element->type))
            $this->_type = $element->type;

        if(isset($element->id))
            $this->_id = $element->id;

        if(isset($element->name))
            $this->_name = $element->name;

        if(isset($element->htmlName))
            $this->_htmlName = $element->htmlName;

        if(isset($element->style))
            $this->_style = $element->style;

        if(isset($element->displayType))
            $this->_displayType = $element->displayType;

        if(isset($element->dataType))
            $this->_dataType = $element->dataType;

        if(isset($element->createdFromContactFieldId))
            $this->_createdFromContactFieldId = $element->createdFromContactFieldId;

        if(isset($element->optionListId))
            $this->_optionListId = $element->optionListId;


        if(isset($element->validations)){
            foreach($element->validations as $validationObject){
                $validationClass = __NAMESPACE__ . '\Validation\\'.$validationObject->condition->type;
                $validationRule = new $validationClass($validationObject);
                $this->_validations[] = $validationRule;
            }
        }
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return null|string
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * @return int|null
     */
    public function getCreatedFromContactFieldId()
    {
        return $this->_createdFromContactFieldId;
    }

    /**
     * @return null|string
     */
    public function getDisplayType()
    {
        return $this->_displayType;
    }

    /**
     * @return null|string
     */
    public function getDataType() {
        return $this->_dataType;
    }

    /**
     * Get options
     *
     * @return bool|FieldOption[]
     */
    public function getOptionList(){
        if(0 == $this->_optionListId)
            return false;

        if(!count($this->_options))
            $this->retrieveOptionList();

        return $this->_options;

    }

    /**
     * @throws Exception
     */
    protected function retrieveOptionList(){
        $optionListResponse = $this->_client->performRequest(sprintf(self::API_ENDPOINT, $this->_optionListId), null, Client::HTTP_GET);
        $json = \GuzzleHttp\json_decode($optionListResponse->getBody()->getContents());

        if(null === $json)
            throw new Exception('Options list could not be decoded');

        foreach($json->elements as $optionObject){
            $option = new FieldOption($optionObject);
            $this->_options[] = $option;
        }
    }

    /**
     * @return null|string
     */
    public function getHtmlName()
    {
        return $this->_htmlName;
    }

    /**
     * @return bool
     */
    public function isUseGlobalSubscriptionStatus()
    {
        return $this->_useGlobalSubscriptionStatus;
    }

    /**
     * @return array
     */
    public function getValidations()
    {
        return $this->_validations;
    }

    /**
     * Validate the field value
     *
     * @param $value
     * @return bool
     */
    public function validate($value){
        foreach($this->_validations as $validation){
            if(false === $validation->validate($value))
                return false;
        }
        return true;
    }

    /**
     * Return field name
     * @return string
     */
    function __toString()
    {
        return $this->_htmlName;
    }

}