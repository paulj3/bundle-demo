<?php
/**
 *  Copyright 2018 Vanderbilt University Medical Center
 */

namespace Victr\OncoreApiBundle\Util;

/**
 * Interface OncoreSoapInterface
 * @package Victr\OncoreApiBundle\Util
 */
interface OncoreSoapInterface
{
    public function modifyRequest($response);
}