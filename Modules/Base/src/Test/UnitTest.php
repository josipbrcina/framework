<?php

namespace Framework\BaseTest;

use Framework\RestApi\Module;
use Framework\RestApi\RestApi;
use PHPUnit\Framework\TestCase;

/**
 * All tests should extend this class
 *
 * Class UnitTest
 * @package Framework\BaseTest
 */
class UnitTest extends TestCase
{
    /**
     * @var RestApi|null
     */
    private $application = null;

    /**
     * @return RestApi|null
     */
    protected function getApplication()
    {
        if ($this->application === null) {
            $this->application = new RestApi([
                Module::class
            ]);

            $this->application->buildRequest();
        }

        return $this->application;
    }
}