<?php

namespace Matks\GitSpam\tests\Units\Commit;

use Matks\GitSpam\Commit;

use \atoum;

class YouTrackCommitReader extends atoum
{
    public function testConstruct()
    {
        $reader = new Commit\YouTrackCommitReader();

        $this
            ->class(get_class($reader))
                ->hasInterface('\Matks\GitSpam\Commit\CommitReaderInterface')
        ;
    }

    public function testStandardCommitsAnalysis()
    {
        $reader = new Commit\YouTrackCommitReader();

        $commits = array(
            array('commit' => array('message' => 'Lol !')),
            array('commit' => array('message' => 'ABC-129: hahaha')),
            array('commit' => array('message' => 'Hum ...: that does not work')),
            array('commit' => array('message' => 'Shitty commit::')),
            array('commit' => array('message' => 'RepNot-128: test'))
        );

        $results = $reader->analyseCommits($commits);

        $expectedArray = array('ABC-129', 'RepNot-128');
        $this
            ->array($results)
                ->isEqualTo($expectedArray)
        ;
    }

    public function testCommitWithErrorsAnalysis()
    {
        $reader = new Commit\YouTrackCommitReader();

        $commits = array(
            array('commit' => array('message' => 'Thread67 :lpo')),
            array('commit' => array('message' => 'NotInGoodShape5: hello')),
            array('commit' => array('message' => 'BadProject-100 shines'))
        );

        $results = $reader->analyseCommits($commits);

        $expectedArray = array('Thread-67', 'NotInGoodShape-5', 'BadProject-100');
        $this
            ->array($results)
                ->isEqualTo($expectedArray)
        ;
    }
}
