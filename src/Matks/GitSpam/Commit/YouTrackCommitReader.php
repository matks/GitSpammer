<?php

namespace Matks\GitSpam\Commit;

/**
 * Commit Reader looking for YouTrack Project IDs in commit messages
 */
class YouTrackCommitReader implements CommitReaderInterface
{
    const YOUTRACK_COMMIT_ID_REGEX  = '[a-zA-Z]+-?\d+';

    const YOUTRACK_PROJECT_ID_REGEX = '[a-zA-Z]+-\d+$';

    /**
	 * Capture YouTrack project ID in the given commits list
	 *
	 * @param  array $commits
	 * @return array
	 *
	 * @example given commit message "Maint-1055: fix player"; "Maint-1055" is extracted
	 */
    public function analyseCommits(array $commits)
    {
        $result = array();

        foreach ($commits as $commit) {
            $message = $commit['commit']['message'];

            $projectID = $this->findYouTrackProjectID($message);
            if (!$projectID) {
                continue;
            }

            $result[] = $projectID;
        }

        return $result;
    }

    /**
	 * Attempts to find a YouTrack project ID in given string
	 *
	 * @param  string $message
	 * @return string|null
	 */
    private function findYouTrackProjectID($message)
    {
        $regexPattern = '#' . static::YOUTRACK_COMMIT_ID_REGEX . '#';

        $matches = array();
        $match = preg_match($regexPattern, $message, $matches);

        if ($match === 0) {
            return null;
        }

        $projectID = $matches[0];
        $cleanProjectID = $this->cleanProjectID($projectID);

        if (!$cleanProjectID) {
            return null;
        }

        return $cleanProjectID;
    }

    /**
	 * Fix YouTrack approximative project ID
	 *
	 * @param  string $rawProjectID
	 * @return string|null
	 */
    private function cleanProjectID($rawProjectID)
    {
        $rawProjectID = str_replace(' ', '', $rawProjectID);
        $regexPattern = '#' . static::YOUTRACK_PROJECT_ID_REGEX . '#';

        $matches = array();
        $match = preg_match($regexPattern, $rawProjectID, $matches);

        if ($match === 1) {
            return $rawProjectID;
        } else {
            $parts = array();
            preg_match('#([a-zA-Z]+)-?(\d+)#', $rawProjectID, $parts);

            if (count($parts) !== 3) {
                return null;
            }
            $projectName = $parts[1];
            $ID = $parts[2];

            return $projectName . '-' . $ID;
        }
    }
}
