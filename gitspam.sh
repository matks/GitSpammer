#!/bin/bash

function runGitSpam() {
	echo ""
	php ./gitspam.php $1 $2 $3 $4 $5
}

if [ $# -ne 4 ]
then
	echo "gitspam.sh <username> <repositoryOwner> <repositoryName> <pullRequestID>"
	exit
fi

username=$1
repository_owner=$2
repository_name=$3
pull_request_id=$4

read -s -p "Enter your github password: " password

runGitSpam $username $password $repository_owner $repository_name $pull_request_id