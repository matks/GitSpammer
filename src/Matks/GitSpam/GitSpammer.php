<?php

namespace Matks\GitSpam;

use Matks\GitSpam\Commit\CommitReaderInterface;
use Github\Client as GithubAPIClient;

use Exception;

class GitSpammer
{
    /**
     * @var Github\Client
     */
    private $githubClient;

    /**
     * Commit analyser able to extract pieces of data from commit messages
     * @var CommitReader
     */
    private $commitReader;

    public function __construct(GithubAPIClient $githubClient, CommitReaderInterface $commitReader)
    {
        $this->githubClient = $githubClient;
        $this->commitReader = $commitReader;
    }

    public function analysePR($username, $repositoryName, $pullRequestID)
    {
        $commits = $this->githubClient
                            ->api('pull_request')
                            ->commits($username, $repositoryName, $pullRequestID)
        ;
        
        if (!$commits) {
            throw new Exception('Cannot fetch Pull Request data');
        }

        $commitsAnalysisResults = $this->commitReader->analyseCommits($commits);
        return $commitsAnalysisResults;
    }
}
