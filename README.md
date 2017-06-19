# SQweb Laravel Package

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel.svg?branch=master)](https://travis-ci.org/SQweb-team/SQweb-SDK-Laravel)
[![Latest Stable Version](https://poser.pugx.org/sqweb/laravel_sdk/v/stable)](https://packagist.org/packages/sqweb/laravel_sdk)
[![Dependency Status](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599/badge.svg)](https://www.versioneye.com/user/projects/570672f9fcd19a0051854599)

**This package allows you to easily integrate SQweb on your Laravel powered website.**

## Requirements

**This SDK has been tested with PHP 5.5 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

## Install

**This package is intended for websites powered by Laravel, and advanced integrations.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

### Using Composer

1. In your project root, execute `composer require sqweb/laravel_sdk`;

2. Now, go to config/app.php and add this line to your providers array:

    ```php
    Sqweb\Laravel_sdk\SqwebServiceProvider::class,
    ```

3. Type `php artisan vendor:publish` at the root of your project to create the configuration file.

4. In `.env`, paste the following configuration and **set the variable `SQW_ID_SITE` with your website ID and the variable `SQW_SITENAME` with the name you want to show on the large multipass button**.

    ```php
    SQW_ID_SITE=YOUR_WEBSITE_ID
    SQW_SITENAME=YOUR_WEBSITE_NAME
    ```

For additional settings, see "[Options](#options)" below.

## Usage

The SDK is really simple to use. Here's how to:

### 1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{ $sqweb->script() }}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

### 2. Checking the credits of your subscribers

This function checks if the user has credits, so that you can disable ads and/or unlock premium content.

Use it like this:

```php
@if ($sqweb->checkCredits())
    // CONTENT
@else
    // ADS
@endif
```

### 3. Showing the Multipass button

Finally, use this code to get the Multipass button on your pages:

```php
{{ $sqweb->button() }}
```

We have differents sizes for the button, to use them, pass a string to the function e.g:

```php
{{ $sqweb->button('tiny') }}
{{ $sqweb->button('slim') }}
{{ $sqweb->button('large') }}
```

![Example Buttons](https://cdn.multipass.net/github/buttons@2x.png "Example Buttons")

### 4. More functions

#### Display a support div for your users

```php
/**
 * Display a support block.
 */

function supportBlock() {   }
``

For instance:

```php
{!! $sqweb->supportBlock !!}
```

Will display the block.

#### Display a locking div for your users

```php
/**
 * Display a locking block.
 */

function supportBlock() {   }
``

For instance:

```php
{!! $sqweb->lockingBlock !!}
```

We recommand you to use it in combination with our filter functions, like this:

```php
@if($sqweb->waitToDisplay('2016-09-15', 2))
    // The content here will appear the 2016-09-17, 2 days after the publication date for non paying users.
@else
    // Here you can put content that free users will see before the content above is available for all.
    // {!! $sqweb->lockingBlock !!}
@end
```

#### Display only a part of your content to non premium users

```php
/**
 * Put opacity to your text
 * Returns the text with opcaity style.
 * @param text, which is your text.
 * @param int percent which is the percent of your text you want to show.
 * @return string
 */
    public function transparent($text, $percent = 100) { ... }
```

Example:

```php
{!! $sqweb->transparent('one two three four', 50) !!}
```

Will display for free users:

```text
one two
```

#### Display your content later for non paying users

```php
    /**
     * Display your premium content at a later date to non-paying users.
     * @param  string  $date  When to publish the content on your site. It must be an ISO format(YYYY-MM-DD).
     * @param  integer $wait  Days to wait before showing this content to free users.
     * @return bool
     */
    public function waitToDisplay($date, $wait = 0) { ... }
```

Example:

```php
@if($sqweb->waitToDisplay('2016-09-15', 2))
    // The content here will appear the 2016-09-17, 2 days after the publication date for non paying users.
@else
    // Here you can put content that free users will see before the content above is available for all.
@end
```

#### Limit the number of articles free users can read per day

```php
    /**
     * Limit the number of articles free users can read per day.
     * @param $limitation int The number of articles a free user can see.
     * @return bool
     */
    public function limitArticle($limitation = 0) { ... }
```

Example if I want to display only 5 articles to free users:

```php
@if ($sqweb->limitArticle(5) == true)
    echo "This is my article";
@else
    echo "Sorry, you reached the limit of pages you can see today, come back tomorrow or subscribe to Multipass to get unlimited articles !";
@endif
```

## Options

Unless otherwise noted, these options default to `false`. You can set them in your `.env` file.

|Option|Description
|---|---|
|`SQW_MESSAGE`|A custom message that will be shown to your adblockers. Quotes must be escaped.|
|`SQW_ADBLOCK_MODAL`|Automatically display the Multipass modal to detected adblockers.|
|`SQW_TARGETING`|Only show the button to detected adblockers. Cannot be combined with `beacon` mode.|
|`SQW_BEACON`|Monitor adblocking rates quietly, without showing any button or banner to the end users.|
|`SQW_DEBUG`|Output various messages to the browser console while the plugin executes.|
|`SQW_DWIDE`|Set to `false` to only enable SQweb on the current domain. Defaults to `true`.|
|`SQW_LANG`|You may pick between `en` and `fr`.|

## Contributing

We welcome contributions and improvements.

### Coding Style

All PHP code must conform to the [PSR2 Standard](http://www.php-fig.org/psr/psr-2/).

### Builds and Releases

See [RELEASE.md](RELEASE.md).

## Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please e-mail `security@sqweb.com`. Vulnerabilities will be promptly addressed.

## License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
