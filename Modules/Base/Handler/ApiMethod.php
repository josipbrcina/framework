<?php

namespace Framework\Base\Handler;

use Framework\Application\RestApi\ApplicationAwareInterface;
use Framework\Application\RestApi\ApplicationAwareTrait;
use Framework\Http\Request\ApiMethodInterface;

abstract class ApiMethod implements ApiMethodInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @return array
     */
    public function getRegisteredRequestMethods()
    {
        return array_keys($this->getRegisteredRequestRoutes());
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $requestMethod = $this->getApplication()
            ->getRequest()
            ->getMethod();

        return call_user_func([$this, $this->getRegisteredRequestRoutes()[$requestMethod]]);
    }

    /**
     * @return \Framework\Base\Model\RepositoryManagerInterface
     */
    public function getRepositoryManager()
    {
        if ($this->getApplication()->getRepositoryManager() === null) {
            throw new \RuntimeException('Repository manager not set');
        }

        return $this->getApplication()->getRepositoryManager();
    }

    /**
     * @param $fullyQualifiedClassName
     * @return \Framework\Base\Model\BrunoRepositoryInterface
     */
    public function getRepository($fullyQualifiedClassName)
    {
        return $this->getRepositoryManager()
            ->getRepository($fullyQualifiedClassName);
    }
}