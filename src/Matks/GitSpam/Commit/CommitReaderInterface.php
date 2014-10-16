<?php

namespace Matks\GitSpam\Commit;

interface CommitReaderInterface
{
    /**
	 * Analyses commit messages to extract pieces of data
	 *
	 * @param  array  $commits
	 * @return array
	 */
    public function analyseCommits(array $commits);
}
