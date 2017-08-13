<?php

namespace Framework\GenericCrud\Api;

use Framework\Base\Database\MongoAdapter;
use Framework\Base\Module\BaseModule;
use Framework\GenericCrud\Api\Model\Generic as GenericModel;
use Framework\GenericCrud\Api\Repository\UserRepository;

/**
 * Class Api
 * @package Framework\GenericCrud\Api
 */
class Module extends BaseModule
{
    private $config = [
        'routes' => [
            [
                'get',
                '/{resourceName}',
                '\Framework\GenericCrud\Api\Controller\Resource::loadAll'
            ],
            [
                'get',
                '/{resourceName}/{identifier}',
                '\Framework\GenericCrud\Api\Controller\Resource::load'
            ],
            [
                'post',
                '/{resourceName}',
                '\Framework\GenericCrud\Api\Controller\Resource::create'
            ],
            [
                'put',
                '/{resourceName}/{identifier}',
                '\Framework\GenericCrud\Api\Controller\Resource::update'
            ],
            [
                'patch',
                '/{resourceName}/{identifier}',
                '\Framework\GenericCrud\Api\Controller\Resource::partialUpdate'
            ],
            [
                'delete',
                '/{resourceName}/{identifier}',
                '\Framework\GenericCrud\Api\Controller\Resource::delete'
            ],
        ],
        'repositories' => [
            GenericModel::class => UserRepository::class
        ]
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap()
    {
        $application = $this->getApplication();

        $application->getDispatcher()
            ->addRoutes($this->config['routes']);

        $mongoAdapter = new MongoAdapter();

        $application->getRepositoryManager()
            ->registerRepositories($this->config['repositories'])
            ->setDatabaseAdapter($mongoAdapter);
    }
}