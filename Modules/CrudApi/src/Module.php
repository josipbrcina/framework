<?php

namespace Framework\CrudApi;

use Framework\Base\Module\BaseModule;
use Framework\CrudApi\Repository\GenericRepository;

/**
 * Class Module
 * @package Framework\CrudApi
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public function bootstrap()
    {
        $application = $this->getApplication();

        // Let's read all files from module config folder and set to Configuration
        $appConfigPath = $this->getApplication()
                              ->getConfiguration()
                              ->getPathValue('env.APPLICATION_CONFIG_PATH');
        $configDirPath = $application->getRootPath() . $appConfigPath;
        $this->setModuleConfiguration($configDirPath);
        $appConfig = $application->getConfiguration();

        // Add routes to dispatcher
        $application->getDispatcher()
                    ->addRoutes($appConfig->getPathValue('routes'));

        // Add acl rules
        $application->setAclRules($appConfig->getPathValue('acl'));

        // Format models configuration
        $modelsConfiguration = $this->generateModelsConfiguration(
            $appConfig->getPathValue('models')
        );

        $repositoryManager = $application->getRepositoryManager();

        $modelAdapters = $appConfig->getPathValue('modelAdapters');
        // Register model adapters
        foreach ($modelAdapters as $model => $adapters) {
            foreach ($adapters as $adapter) {
                $repositoryManager->addModelAdapter($model, new $adapter());
            }
        }

        $primaryModelAdapter = $appConfig->getPathValue('primaryModelAdapter');
        // Register model primary adapters
        foreach ($primaryModelAdapter as $model => $primaryAdapter) {
            $repositoryManager->setPrimaryAdapter($model, new $primaryAdapter());
        }

        //Register Services
        $services = $appConfig->getPathValue('services');
        foreach ($services as $service) {
            $application->registerService(new $service);
        }

        //Add cron jobs
        $cronJobs = $appConfig->getPathValue('cronJobs');
        foreach ($cronJobs as $job => $params) {
            $cronJob = new $job($params);
            $application->registerCronJob($cronJob);
        }

        // Register resources, repositories and model fields
        $repositoryManager->registerResources($modelsConfiguration['resources'])
                          ->registerRepositories($appConfig->getPathValue('repositories'))
                          ->registerModelFields($modelsConfiguration['modelFields']);
    }

    /**
     * @param $modelsConfig
     * @return array
     */
    private function generateModelsConfiguration(array $modelsConfig)
    {
        $generatedConfiguration = [
            'resources' => [],
            'modelFields' => [],
        ];
        foreach ($modelsConfig as $modelName => $options) {
            $generatedConfiguration['resources'][$options['collection']] = GenericRepository::class;
            $generatedConfiguration['modelFields'][$options['collection']] = $options['fields'];
        }

        return $generatedConfiguration;
    }
}
