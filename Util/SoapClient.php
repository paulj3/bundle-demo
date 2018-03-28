<?php
/**
 * Copyright 2018 Vanderbilt University Medical Center
 */

namespace Victr\OncoreApiBundle\Util;


/**
 * Custom soap client for the OnCore API.
 *
 * The OnCore API uses MTOM encoding for responses, which is not supported by
 * PHP's native SoapClient. This class extends the native client and override
 * __doRequest to parse out the MTOM headers and return just the SOAP+XML part
 * of the response.
 *
 * @package UberBundle\Service
 */
class SoapClient extends \SoapClient implements OncoreSoapInterface
{

    /**
     * Override parent method to support MTOM encoded SOAP responses.
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);

        $response = $this->modifyRequest($response);

        return $response;
    }

    /**
     * @param string $response
     * @return string
     */
    public function modifyRequest($response)
    {
        // MTOM response should contain a Content-Type: application/xop+xml header
        if (strpos($response, 'Content-Type: application/xop+xml') !== false) {
            $response = stristr(stristr($response, "<soap:"), "</soap:Envelope>", true) . "</soap:Envelope>";
        }
        return $response;
    }

}