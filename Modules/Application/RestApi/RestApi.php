<?php

namespace Framework\Application\RestApi;

use Framework\Application\Base\BaseApplication;
use Framework\Http\Response\Response;

/**
 * Class RestApi
 * @package Framework\Application\RestApi
 */
class RestApi extends BaseApplication
{
    /**
     * @return \Framework\Http\Response\Response
     */
    public function run()
    {
        try {
            $routeHandler = $this->getRouter()
                ->parse($_SERVER['REQUEST_URI']);

            if (!in_array($this->getRequest()->getMethod(), $routeHandler->getRegisteredRequestMethods())) {
                // TODO: implement custom exception for this
                throw new \RuntimeException('Not implemented');
            }

            $routeHandler->setApplication($this);
            $handlerOutput = $routeHandler->handle();
        } catch (\Exception $e) {
            $handlerOutput = $e->getMessage();
        }

        $response = new Response($handlerOutput);

        $this->setResponse($response);

        $response->output();

        return $this->getResponse();
    }
}
