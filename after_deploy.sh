#!/usr/bin/env bash

# SQWEB - LARAVEL SDK - RELEASE NOTIFIER
# ------------------------------------------------------------------------------
# Let the Slack team know that the release was successful.

curl -X "POST" "https://hooks.slack.com/services/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" \
	 -H "Content-Type: application/x-www-form-urlencoded; charset=utf-8" \
	 --data-urlencode "payload={\"text\": \"$TRAVIS_TAG released on GitHub + Packagist.\"}"
