#!/usr/bin/env bash

# SQWEB - POST RELEASE SLACK NOTIFICATION
# ------------------------------------------------------------------------------
# This script is called after a succesful deploy by Travis.

curl -X POST --data-urlencode 'payload={"channel": "#sqw-dev-sdk", "text": "Version '$TRAVIS_TAG' of the Laravel SDK has been released."}' \
	https://hooks.slack.com/services/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
