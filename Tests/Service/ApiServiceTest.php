<?php
/**
 * Copyright 2018 Vanderbilt University Medical Center
 */

namespace Victr\OncoreApiBundle\Tests\Service;

use AppBundle\Zend\Definer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Victr\OncoreApiBundle\Service\ApiService;


class ApiServiceTest extends KernelTestCase
{

    /** @var ApiService */
    private $service;


    /**
     * Boot kernel and set service to charge project data service.
     */
    protected function setUp()
    {
        $definer = new Definer();
        $definer->setDefines(true);
        self::bootKernel();
        $this->service = static::$kernel->getContainer()->get('uber.ap.charge_project_data');
    }


    /**
     * Test getting a protocol by ID
     *
     * @throws \Exception
     */
    public function testGetProtocolById()
    {
        $data = $this->service->getProtocolById('VICCHEM16134');


        //$this->assertInstanceOf(AggregatedProjectModel::class, $requestData);
        //$this->assertEquals('5003', $requestData->getId());
    }

}