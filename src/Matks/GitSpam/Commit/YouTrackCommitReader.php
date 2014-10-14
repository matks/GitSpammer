<?php

namespace Matks\GitSpam\Commit;

use Matks\GitSpam\Commit\CommitReaderInterface;

/**
 * Commit Reader looking for YouTrack Project IDs in commit messages
 */
class YouTrackCommitReader implements CommitReaderInterface
{
	const YOUTRACK_PROJECT_ID_REGEX = '^([^:])*:(.*)$';

	/**
	 * Capture YouTrack project ID in the given commits list
	 *
	 * @example given commit message "Maint-1055: fix player"; "Maint-1055" is extracted
	 */
	public function analyseCommits(array $commits)
	{
		$result = array();

		foreach ($commits as $commit) {
			$formattedPattern = '#' . static::YOUTRACK_PROJECT_ID_REGEX . '#';
			$guessedProjectID = preg_match($formattedPattern, $commit);

			if (count($guessedProjectID) > 1) {
				continue;
			}

			$guessedProjectID = remove_space($guessedProjectID);
			$result[$guessedProjectID] = array(
				'ID' => $guessedProjectID,
				'commit' => $commit
			);
		}

		return $result;
	}
}