<?php

namespace Matks\GitSpam;

use Matks\GitSpam\Commit\YouTrackCommitReader;
use Matks\GitSpam\Configuration\Configuration;
use Matks\GitSpam\GitSpammer;

use Matks\Vivian\Tools;
use Github\Client as GithubAPIClient;


use Exception;

/**
 * GitSpammer Console Manager
 *
 * Build GitSpammer project objects, parse string inputs as arguments
 * for these objects and echo results in a readable manner
 * 
 */
class ConsoleManager
{

    /**
     * Main
     * @param  $repositoryName
     * @param  $pullRequestID
     */
    public static function main($repositoryName, $pullRequestID)
    {
        $consoleManager = new ConsoleManager();
        $consoleManager->run((string)$repositoryName, intval($pullRequestID));
    }

    /**
     * Run
     */
    public function run($repositoryName, $pullRequestID)
    {
        $configuration = new Configuration();
        $configuration->load();

        $gitSpammer = $this->setup($configuration);

        $analysisResults = $gitSpammer->analysePR($repositoryName, $pullRequestID);
    }

    /**
	 * Construct GitSpam instance with its dependencies
     *
     * @param array $configuration
	 * @return GitSpam
	 */
    private function setup(array $configuration)
    {
        $client       = new GithubAPIClient();
        $commitReader = new YouTrackCommitReader();
        $gitSpammer   = new GitSpammer($client, $commitReader);

        return $gitSpammer;
    }
}
