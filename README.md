SQweb Laravel Package
===

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel.svg?branch=master)](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel)
[![Code Climate](https://codeclimate.com/github/SQweb-team/SQweb-SDK-Laravel/badges/gpa.svg)](https://codeclimate.com/github/SQweb-team/SQweb-SDK-Laravel)
[![Dependency Status](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599/badge.svg)](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599)
[![License](https://img.shields.io/badge/license-GPL%20v3-428F7E.svg)](http://opensource.org/licenses/GPL-3.0)

**This package enables you to easily integrate SQweb on your Laravel powered site.**

##Requirements

**This SDK has been tested with PHP 5.5 and greater.**

The current Version of Laravel requires [PHP 5.5.9 or greater](https://laravel.com/docs/5.2#server-requirements).

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

##Install

**This package is intended for websites powered by Laravel, and advanced integrations.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

###Using Composer

1. In your project root, execute `composer require sqweb/laravel_sdk`;
2. In `./config/app.php`, add this line to your providers array : `Sqweb\Laravel_sdk\SqwebServiceProvider::class`.
3. Execute `php artisan vendor:publish` at the root of your project to create the configuration file.
4. Go to `config/sqweb.php` and set your `ID_SITE`.

For additional settings, see "[Options](#options)" below.

##Usage

The SDK is super basic. Here's how to use it :

###1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{ $sqweb->script() }}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

###2. Checking the credits of your subscribers

This function checks if the user has credits, so that you can disable ads and/or unlock premium content.

Use it like this:

```php
@if ($sqweb->checkCredits() > 0)
    //CONTENT
@else
    //ADS
@endif
```

###3. Showing the Multipass button

Finally, use this code to get the Multipass button on your pages:

```php
{{ $sqweb->button() }}
```

###4. More functions

1.This function is used to display only a part of your content to non premium users:
```php
function transparent($your_content, $percent) { ... }
```
`$percent` is the percent of your content you want to display to everyone.

Example:
```php
{{ echo transparent('one two three four', 50) }}
```
Will display for free users:
```
one two
```

2.The following function is used to display your content later for non paying users:
```php
function waitToDisplay($your_content, $publication_date, $date_format, $wait) { ... }
```
`$publication_date` is the date when your content is published on your website.
`$date_format` is the format of your publication date ('Y-m-d' for '2016-12-18').
`$wait` is the number of day you want to wait before showing this content to free users.

Example:
```php
{{ echo waitToDisplay('I will appear for everyone in 2 days', '15/09/16', 'd/m/y', 2) }}
```

3.The next function is used to limit the number of articles free users can read a day:
```php
function limitArticle($number_of_articles) { ... }
```
`$number_of_articles` is the number of articles a free user can see.

Example if I want to display only 5 articles to free users:
```php
@if (limitArticle(5) == true)
	echo "This is my article";
@else
	echo "Sorry, you reached the limit of pages you can see today, come back tomorrow or subscribe to Multipass to get unlimited articles !";
@endif
```

##Options

Unless otherwise noted, these options default to `false`. You can set them in `config/sqweb.php`, where you defined your `ID_SITE`.

|Option|Description
|---|---|
|`msg`|A custom message that will be shown to your adblockers. If using quotes, you must escape them.|
|`targeting`|Only show the button to detected adblockers. Cannot be combined with the `beacon` mode.|
|`beacon`|Monitor adblocking rates quietly, without showing a SQweb button or banner to the end users.|
|`debug`|Output various messages to the browser console while the plugin executes.|
|`dwide`|Set to `false` to only enable SQweb on the current domain. Defaults to `true`.|
|`lang`|You may pick between `en` and `fr`.|


##Contributing

We welcome contributions and improvements.

###Coding Style

All PHP code must conform to the [PSR2 Standard](http://www.php-fig.org/psr/psr-2/).

##Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please send an e-mail to `hello@sqweb.com`. All security vulnerabilities will be promptly addressed.

##License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.