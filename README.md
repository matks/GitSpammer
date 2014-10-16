GitSpammer
==========

This is an attempt to computerize some procedures I currently do manually based on git events.
Example: send mails with a summary of what features are implemented in a specific Pull Request.

## Installation

Install the dependencies with composer
```bash
composer install
```

## Tests

Install the dev dependencies with composer
```bash
composer install --dev
```

Run the unit tests suite with atoum binary.
```bash
vendor/bin/atoum -bf vendor/autoload.php -d tests/Units/
```

## Running GitSpammer

Run the following command:
```bash
$ bash gitspam.sh <username> <repositoryOwner> <repositoryName> <pullRequestID>
```