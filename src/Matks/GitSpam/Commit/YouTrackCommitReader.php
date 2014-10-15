<?php

namespace Matks\GitSpam\Commit;

use Matks\GitSpam\Commit\CommitReaderInterface;

/**
 * Commit Reader looking for YouTrack Project IDs in commit messages
 */
class YouTrackCommitReader implements CommitReaderInterface
{
	const YOUTRACK_PROJECT_ID_REGEX = '^([a-zA-Z]*-\d*):[^:]*$';

	/**
	 * Capture YouTrack project ID in the given commits list
	 *
	 * @example given commit message "Maint-1055: fix player"; "Maint-1055" is extracted
	 */
	public function analyseCommits(array $commits)
	{
		$result = array();

		foreach ($commits as $commit) {
			$regexPattern = '#' . static::YOUTRACK_PROJECT_ID_REGEX . '#';
			$message      = $commit['commit']['message'];

			$matches = array();
			$match = preg_match($regexPattern, $message, $matches);

			$guessedProjectID = $matches[1];

			if ($match === 0) {
				continue;
			}

			$guessedProjectID = str_replace(' ', '', $guessedProjectID);
			$result[] = $guessedProjectID;
		}

		return $result;
	}
}