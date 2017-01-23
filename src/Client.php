<?php

namespace EloquaForms;

use EloquaForms\Data\Form;
use EloquaForms\Data\Forms;
use GuzzleHttp\Exception\ClientException;

class Client
{
    const HTTP_POST   = 'POST';
    const HTTP_PUT    = 'PUT';
    const HTTP_GET    = 'GET';
    const HTTP_DELETE = 'DELETE';

    const HTTP_PLAIN  = 'http://';
    const HTTP_SSL    = 'https://';

    /**
     * The Eloqua host
     *
     * @var string
     */
    private $_host = 'secure.eloqua.com';

    /**
     * Eloqua username
     *
     * @var string|null
     */
    private $_username = null;

    /**
     * Eloqua password
     *
     * @var string|null
     */
    private $_password = null;

    /**
     * Eloqua site name
     *
     * @var string|null
     */
    private $_siteName = null;

    /**
     * Set the connection protocol
     *
     * @var string
     */
    private $_protocol = self::HTTP_SSL;

    /**
     * Return Authentication header
     *
     * @return array
     */
    protected function _authentication(){
        if(null != $this->_username || null != $this->_password || null != $this->_siteName)
            return ['Authorization' => 'Basic ' . base64_encode($this->_siteName . '\\' . $this->_username . ':' . $this->_password)];

        return [];
    }

    /**
     * @param string $siteName
     * @param string $username
     * @param string $password
     * @return Client
     */
    public function setCredentials($siteName, $username, $password){
        $this->_siteName = $siteName;
        $this->_username = $username;
        $this->_password = $password;
        return $this;
    }

    /**
     * Set The Eloqua host to connect to.
     *
     * @param string $hostname
     * @return Client
     */
    public function setHost($hostname){
        $this->_host = $hostname;
        return $this;
    }


    /**
     * Get a Form class to post/retrieve to/from Eloqua
     *
     * @return Form
     */
    public function getForm(){
        return new Form($this);
    }

    /**
     * Get the Forms object
     *
     * @return Forms
     */
    public function getForms(){
        return new Forms($this);
    }

    /**
     * Set the connection protocol
     *
     * @param string $protocol
     * @return Client
     * @throws Exception
     */
    public function setProtocol($protocol)
    {
        if($protocol != self::HTTP_PLAIN && $protocol != self::HTTP_SSL)
            throw new Exception('Set Protocol is incorrect. Please use HTTP_PLAIN | HTTP_SSL', 100);

        $this->_protocol = $protocol;

        return $this;
    }

    /**
     * Send a request to Eloqua
     *
     * @param $endpoint
     * @param null $data
     * @param string $method
     * @throws ClientException|Exception
     * @return \GuzzleHttp\Psr7\Response
     */
    public function performRequest($endpoint, $data = null, $method = self::HTTP_POST){

        if(null == $this->_host)
            throw new Exception('No host has been set.', 110);

        if(null == $this->_username || null == $this->_password || null == $this->_siteName)
            throw new Exception('Authentication credentials are not set', 120);

        $client = new \GuzzleHttp\Client(['base_uri' => $this->_protocol . $this->_host]);
        $parameters = ['headers' => $this->_authentication()];


        if(null != $data && self::HTTP_POST == $method)
            $parameters['json'] = $data;
        try {
            return $client->request($method, $endpoint, $parameters);
        }catch (ClientException $e) {
            $exception = new \EloquaForms\ClientException($e->getMessage(), $e->getCode(), $e->getPrevious());
            $exception->setClientInformation($e);
            throw $exception;
        }
    }
}