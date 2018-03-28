<?php
/**
 *
 *
 * Copyright 2018 Vanderbilt University Medical Center
 */

namespace Victr\OncoreApiBundle\Service;


use Victr\CommonBundle\Service\Environment;
use Victr\OncoreApiBundle\Util\SoapClient;

class ApiService
{

    const API_URL_DEV = 'https://oncore-dev1.app.vumc.org/opas/OpasService';
    const API_WSDL_DEV = self::API_URL_DEV . '?wsdl';
    const API_URL_PROD = 'https://oncore.app.vumc.org/opas/OpasService';
    const API_WSDL_PROD = self::API_URL_PROD . '?wsdl';


    /** @var null|SoapClient */
    private $oncoreSoapClient = null;
    /** @var string */
    private $sbriteLdapUser;
    /** @var string */
    private $sbriteLdapPass;
    /** @var string */
    private $wsdl;
    /** @var string */
    private $url;

    public function __construct($ldapUser, $ldapPass)
    {
        $this->sbriteLdapUser = $ldapUser;
        $this->sbriteLdapPass = $ldapPass;
        if (Environment::isProd()) {
            $this->wsdl = self::API_WSDL_PROD;
            $this->url = self::API_URL_PROD;
        } else {
            $this->wsdl = self::API_WSDL_DEV;
            $this->url = self::API_URL_DEV;
        }
    }

    /**
     * @param $protocolId
     * @return mixed
     * @throws \Exception
     */
    public function getProtocolById($protocolId)
    {
        if (is_null($this->oncoreSoapClient)) {
            $this->init();
        }
        $params = new \StdClass();
        $params->protocolNo = $protocolId;
        $params->irbNo = null;
        $projectSearchCriteria = new \SoapVar($params, SOAP_ENC_OBJECT, 'ProtocolSearchCriteria');

        try {
            $studyResult = $this->oncoreSoapClient->__soapCall('getProtocol', [$projectSearchCriteria]);

        } catch (\Exception $e) {
            throw $e;
        }
        return $studyResult;
    }

    /**
     * @param $protocolId
     * @return mixed
     * @throws \Exception
     */
    public function getStaffById($protocolId)
    {
        if (is_null($this->oncoreSoapClient)) {
            $this->init();
        }
        $staffParams = new \StdClass();
        $staffParams->ProtocolNo = $protocolId;
        $staffParams->LastName = null;
        $staffSearchCriteria = new \SoapVar($staffParams, SOAP_ENC_OBJECT, 'ProtocolStaffSearchCriteria');

        try {
            $staffResult = $this->oncoreSoapClient->__soapCall('getProtocolStaff', [$staffSearchCriteria]);

        } catch (\Exception $e) {
            throw $e;
        }
        return $staffResult;
    }

    private function init()
    {
        $options = [
            'login' => $this->sbriteLdapUser,
            'password' => $this->sbriteLdapPass,
            'trace' => 1
        ];

        $this->oncoreSoapClient = new SoapClient($this->wsdl,
            $options);
        $this->oncoreSoapClient->__setLocation($this->url);
    }
}