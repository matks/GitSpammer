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

    const YOUTRACK_TICKET_BASE_URL = 'http://mobpartner.myjetbrains.com/youtrack/issue/';

    /**
     * Main
     *
     * @param  $username
     * @param  $password
     * @param  $repositoryOwner
     * @param  $repositoryName
     * @param  $pullRequestID
     */
    public static function main($username, $password, $repositoryOwner, $repositoryName, $pullRequestID)
    {
        $consoleManager = new ConsoleManager();
        $consoleManager->run((string) $username, (string) $password, (string) $repositoryOwner, (string)$repositoryName, intval($pullRequestID));
    }

    /**
     * Run
     */
    public function run($username, $password, $repositoryOwner, $repositoryName, $pullRequestID)
    {
        $gitSpammer = $this->setup($username, $password);

        $analysisResults = $gitSpammer->analysePR($repositoryOwner, $repositoryName, $pullRequestID);
        $youtrackLinks = $this->computeYouTrackLinks($analysisResults);

        echo Tools::green('Pull Request ' . $pullRequestID . '['. $repositoryName . '] analyzed:');
        echo PHP_EOL;
        echo Tools::s_phpArray($youtrackLinks);
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
        echo Tools::green('Pull Request ' . $pullRequestID . ' ['. $repositoryName . '] analyzed:');
        echo PHP_EOL;

        if (!empty($results)) {
            echo Tools::s_list1($results);
        } else {
            echo Tools::red('No YouTrack IDs found');
            echo PHP_EOL;
        }
    }

    private function computeYouTrackLinks(array $projectIDs)
    {
        $results = array();
        foreach ($projectIDs as $ID) {
            $results[$ID] = static::YOUTRACK_TICKET_BASE_URL . $ID;
        }

        return $results;
    }
}
