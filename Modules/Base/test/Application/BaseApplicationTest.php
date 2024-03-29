<?php

namespace Framework\Base\Test\Application;

use Framework\Base\Application\Exception\GuzzleHttpException;
use Framework\Base\Application\Exception\MethodNotAllowedException;
use Framework\Base\Logger\MemoryLogger;
use Framework\Base\Logger\Log;
use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Test\UnitTest;
use Framework\Base\Sentry\SentryLogger;
use Framework\Http\Response\Response;

class BaseApplicationTest extends UnitTest
{
    public function testLoggers()
    {
        $payload = 'testPayload';
        $log = new Log($payload);
        $application = $this->getApplication();

        $this->assertAttributeCount(0, 'loggers', $application);


        $application->log($log);

        $this->assertContainsOnlyInstancesOf(MemoryLogger::class, $application->getLoggers());

        $this->assertAttributeCount(1, 'loggers', $application);


        $dsn = getenv('SENTRY_DSN');
        $sl = new SentryLogger();
        $sl->setClient($dsn, \Raven_Client::class);
        $application->addLogger($sl);

        $this->assertContainsOnlyInstancesOf(LoggerInterface::class, $application->getLoggers());

        $this->assertAttributeCount(2, 'loggers', $application);
    }

    public function testGuzzleRequestException1()
    {
        $app = $this->getApplication();

        $this::expectException(MethodNotAllowedException::class);

        $app->httpRequest('test');
    }

    public function testGuzzleRequestException2()
    {
        $app = $this->getApplication();

        $this::expectException(GuzzleHttpException::class);

        $app->httpRequest('post', 'http://www.google.com');
    }

    public function testGuzzleRequestException3()
    {
        $app = $this->getApplication();

        $this::expectException(GuzzleHttpException::class);

        $app->httpRequest('get', 'http://www.poqwjdoqwidjqowinhdqwiohqwoiqdhwlokiqwndhqwloi.fr');
    }

    public function testGuzzleRequest()
    {
        $app = $this->getApplication();

        $response = $app->httpRequest('get', 'http://www.google.com');

        $this::assertInstanceOf(
            Response::class,
            $response
        );
        $this::assertEquals(200, $response->getCode());
    }

    public function testRootPath()
    {
        $path = realpath(
            dirname(__DIR__, 4)
        );

        $this::assertEquals(
            $path,
            $this->getApplication()
                 ->getRootPath()
        );
    }
}
