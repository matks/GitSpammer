<?php

namespace Matks\GitSpam;

use Matks\GitSpam\Commit\YouTrackCommitReader;
use Matks\GitSpam\Configuration\ConfigurationLoader;
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
     *
     * @param  $username
     * @param  $password
     * @param  $repositoryName
     * @param  $pullRequestID
     */
    public static function main($username, $password, $repositoryName, $pullRequestID)
    {
        $consoleManager = new ConsoleManager();
        $consoleManager->run((string) $username, (string) $password, (string)$repositoryName, intval($pullRequestID));
    }

    /**
     * Run
     */
    public function run($username, $password, $repositoryName, $pullRequestID)
    {
        $gitSpammer = $this->setup($username, $password);

        $analysisResults = $gitSpammer->analysePR($username, $repositoryName, $pullRequestID);
        $this->writePRAnalysisResult($repositoryName, $pullRequestID, $analysisResults);
    }

    /**
	 * Construct GitSpam instance with its dependencies
     *
	 * @return GitSpam
	 */
    private function setup($username, $password)
    {
        $client = new GithubAPIClient();
        $client->authenticate($username, $password);

        $commitReader = new YouTrackCommitReader();
        $gitSpammer   = new GitSpammer($client, $commitReader);

        return $gitSpammer;
    }

    private function writePRAnalysisResult($repositoryName, $pullRequestID, $results)
    {
        echo Tools::green('Pull Request' . $pullRequestID . ' (repository '. $repositoryName . ') analyzed:');
        echo PHP_EOL;

        if (!empty($results)) {
            echo Tools::s_list1($results);
        } else {
            echo Tools::red('No YouTrack IDs found');
        }
        echo PHP_EOL;
    }
}
