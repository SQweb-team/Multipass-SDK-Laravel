SQweb Laravel Package
===

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel.svg?branch=master)](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel)
[![License](https://img.shields.io/badge/license-GPL%20v3-428F7E.svg)](http://opensource.org/licenses/GPL-3.0)

**This package enables you to easily integrate SQweb on your Laravel powered site.**

##Install

**This package is intended for custom PHP websites, with Laravel, and advanced integrations.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

###Using Composer

1. In your project root, execute `composer require sqweb/laravel_sdk`;
2. In the constructor of `sqweb/laravel_sdk/src/SqwebController.php`, define your site_id by writing `$this->id_site = 'YOUR ID_SITE'`.

For additional settings, see "[Options](#options)" below.

##Usage

The SDK is super basic. Here's how to use it :

###1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{$sqweb->script();}}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

###2. Checking the credits of your subscribers

This function checks if the user has credits, so that you can disable ads and/or unlock premium content.

You can use it like this :

```php
@if ($sqweb->checkCredits() > 0)
    //CONTENT
@else
    //ADS
@endif
```

###3. Showing the SQweb button

Finally, use this code to get the SQweb button on your pages:

```php
{{$sqweb->button('blue')}}
```

This function takes one optional parameter, the color. You can switch between `blue` (default) and `grey`.

##Options

Unless otherwise noted, these options default to `false`. You can set them in the constructor, where you defined your `ID_SITE`.

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

Copyright (C) 2015 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.