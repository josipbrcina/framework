<?php

namespace Framework\RestApi;

use Framework\Base\Application\ApplicationConfiguration;
use Framework\Base\Application\BaseApplication;
use Framework\Http\Render\Json;
use Framework\Http\Request\Request;
use Framework\Http\Response\Response;
use Framework\Http\Router\Dispatcher;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class RestApi
 * @package Framework\RestApi
 */
class RestApi extends BaseApplication
{
    /**
     * RestApi constructor.
     * @param ApplicationConfiguration|null $applicationConfiguration
     */
    public function __construct(ApplicationConfiguration $applicationConfiguration = null)
    {
        $this->setRenderer(new Json());
        $this->setDispatcher(new Dispatcher());
        $this->setResponse(new Response());

        parent::__construct($applicationConfiguration);
    }

    /**
     * @inheritdoc
     */
    public function buildRequest()
    {
        $request = new Request();

        $helperRequest = SymfonyRequest::createFromGlobals();

        $ctHeader = $helperRequest->headers->get('Content-Type');
        if (strpos($ctHeader, 'application/json') === 0) {
            $data = json_decode($helperRequest->getContent(), true);
            $helperRequest->request->replace(is_array($data) ? $data : array());
        }

        $request->setServer($helperRequest->server->all());
        $request->setClientIp($helperRequest->getClientIp());

        $request->setPost($helperRequest->request->all());
        $request->setQuery($helperRequest->query->all());
        $request->setFiles($helperRequest->files->all());
        $request->setCookies($helperRequest->cookies->all());
        $request->setUri($helperRequest->getRequestUri());

        $requestMethod = $request->getMethod();

        if (($requestMethod === 'PUT' || $requestMethod === 'PATCH')
            && (strpos($ctHeader, 'application/json') === 0) === false
        ) {
            $request->setPost($request->getQuery());
        }

        unset($_POST);
        unset($_GET);
        unset($_FILES);
        unset($_COOKIE);

        $this->setRequest($request);

        return $request;
    }
}
