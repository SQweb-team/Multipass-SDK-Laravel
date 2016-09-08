SQweb Laravel Package
===

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel.svg?branch=master)](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel)
[![Code Climate](https://codeclimate.com/github/SQweb-team/SQweb-SDK-Laravel/badges/gpa.svg)](https://codeclimate.com/github/SQweb-team/SQweb-SDK-Laravel)
[![Dependency Status](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599/badge.svg)](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599)
[![License](https://img.shields.io/badge/license-GPL%20v3-428F7E.svg)](http://opensource.org/licenses/GPL-3.0)

**This package allows you to easily integrate SQweb on your Laravel powered website.**

##Requirements

**This SDK has been tested with PHP 5.5 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

##Install

**This package is intended for websites powered by Laravel, and advanced integrations.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

###Using Composer

1. In your project root, execute `composer require sqweb/laravel_sdk`;
2. Now, go to config/app.php and add this line to your providers array : `Sqweb\Laravel_sdk\SqwebServiceProvider::class.
3. Type `php artisan vendor:publish` at the root of your project to create the configuration file.
4. Go to `config/sqweb.php` and set your `ID_SITE`, others options are detail further on this page.

For additional settings, see "[Options](#options)" below.

##Usage

The SDK is really simple to use. Here's how to:

###1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{$sqweb->script();}}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

###2. Checking the credits of your subscribers

This function checks if the user has credits, so that you can disable ads and/or unlock premium content.

Use it like this:

```php
@if ($sqweb->checkCredits())
    //CONTENT
@else
    //ADS
@endif
```

###3. Showing the SQweb button

Finally, use this code to get the SQweb button on your pages:

```php
{{$sqweb->button()}}
```

##Options

Set these environment variables in your .env file to enable or disable them.

|Option|Description
|---|---|
|`SQWEB_SITE_ID`|Sets your website SQweb ID. Ex: 123456.|
|`SQWEB_DEBUG`|Outputs various messages to the browser console while the plugin executes. Disabled by default. 1 (activated) or 0 (deactivated).|
|`SQWEB_TARGET`|Only shows the button in order to detect adblockers. Disabled by default. 1 (activated) or 0 (deactivated).|
|`SQWEB_DWIDE`|Enables SQweb on the current domain. Enabled by default. 1 (activated) or 0 (deactivated).|
|`SQWEB_LANG`|Sets the plugin language. Currently supports `en` (English) and `fr` (French). Defaults to `en`|
|`SQWEB_MESSAGE`|A custom message is displayed to users with an adblocker enabled. Ex:"Please deactivate your adblocker on this website, or support us by using Multipass!". Empty by default.|
|`SQWEB_LIMIT_ARTICLE`|Limits the number of posts/articles or content seen by non-Multipass users per day. Ex: 10. Defaults to 5.|


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
