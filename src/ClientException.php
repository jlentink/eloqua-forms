<?php

namespace EloquaForms;


class ClientException extends Exception
{

    /**
     * @var \GuzzleHttp\Exception\ClientException|null
     */
    private $_clientException = null;

    /**
     * @param \GuzzleHttp\Exception\ClientException $clientException
     */
    public function setClientInformation($clientException){
        $this->_clientException = $clientException;
    }

    /**
     * @return string
     */
    public function getLongMessage()
    {
        return $this->_clientException->getResponse()->getBody()->getContents();
    }



}