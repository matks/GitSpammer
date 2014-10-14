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
        $pullRequest = $this->githubClient
                            ->api('pull_request')
                            ->show($username, $repositoryName, $pullRequestID)
        ;

        var_dump($pullRequest);die();
        // imaginary code
        
        if (!$pullRequest) {
            throw new Exception('Cannot fetch Pull Request data');
        }

        $commits = $pullRequest->getCommits();
        $commitsAnalysisResults = $this->commitReader->analyseCommits($commits);

        return $commitsAnalysisResults;
    }
}
