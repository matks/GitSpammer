<?php

namespace Matks\GitSpam\Configuration;

use Symfony\Component\Yaml\Parser;
use Exception;

class Configuration
{
	private $yamlParser;

	public function __construct()
	{
		$this->yamlParser = new Parser();
	}

	/**
	 * Load configuration file
	 * @return array
	 * @throws Exception if configuration is irrelevant or uncomplete
	 */
    public function load()
    {
        $configurationFilepath = $this->getConfigurationFilepath();
        $configurationFile     = file_get_contents($configurationFilepath);

        $projectConfiguration = $this->yamlParser->parse($configurationFile);
        $this->validateConfiguration($projectConfiguration);

        return $projectConfiguration;
    }

    private function getConfigurationFilepath()
    {
        return __DIR__ . '../../../config/configuration.yml'
    }

    /**
     * Validate configuration array
     * 
     * @param  array $configuration
     * @throws Exception
     */
    private function validateConfiguration($configuration)
    {
        if (!is_array($configuration)) {
            throw new Exception("Configuration must be an array");
        }

        $configurationKeys = array(
            'username'        => 'string',
        );

        foreach ($configurationKeys as $requiredKey => $type) {
            if (!array_key_exists($requiredKey, $configuration)) {
                throw new Exception("Configuration not valid");
            }

            if (!($configuration[$requiredKey] instanceof $type)) {
                throw new Exception("Configuration element $requiredKey is not a $type as expected");
            }
        }
    }
}