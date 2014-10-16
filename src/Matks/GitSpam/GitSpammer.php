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

    /**
     * @param GithubAPIClient       $githubClient
     * @param CommitReaderInterface $commitReader
     */
    public function __construct(GithubAPIClient $githubClient, CommitReaderInterface $commitReader)
    {
        $this->githubClient = $githubClient;
        $this->commitReader = $commitReader;
    }

    /**
     * Analyse Pull Request
     * 
     * @param  string  $repositoryOwner
     * @param  string  $repositoryName
     * @param  integer $pullRequestID
     * @return array
     */
    public function analysePR($repositoryOwner, $repositoryName, $pullRequestID)
    {
        $commits = $this->githubClient
                            ->api('pull_request')
                            ->commits($repositoryOwner, $repositoryName, $pullRequestID)
        ;

        if (!$commits) {
            throw new Exception('Cannot fetch Pull Request data');
        }

        $commitsAnalysisResults = $this->commitReader->analyseCommits($commits);

        return $commitsAnalysisResults;
    }
}
