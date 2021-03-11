# See in REST API [![Latest Stable Version](https://img.shields.io/packagist/v/humanmade/see-in-rest-api.svg)](https://packagist.org/packages/humanmade/see-in-rest-api) 

> Quickly request the current resource off the WordPress REST API via the WordPress admin bar.

----

## Introduction

This plugin adds a new node to the WordPress admin bar, _See in REST API_, that allows for requesting the current resource off the WordPress REST API.
This works both in the WordPress admin and on the front end of your site.

Supported resources:

- posts (including attachments);
- terms;
- users (including your profile).

The plugin does **not** perform any checks around permissions.
If a resource is not available or readable, you will find out after you clicked the link. 🙂

## Installation

**Requirements:**

- PHP 7.3 or higher;
- WordPress 5.5 or higher.

Install with [Composer](https://getcomposer.org):

```sh
composer require humanmade/see-in-rest-api
```

This plugin follows [Semantic Versioning](https://semver.org/).

## Usage

### PHP Filters

#### `see_in_rest_api.rest_url`

This filter allows the user to modify the URL that the admin bar node will link to.

**Arguments:**

* `$rest_url` (`string`): REST API URL for the current resource.

**Usage Example:**

```php
// Show the See in REST API admin bar node for administrators only.
add_filter( 'see_in_rest_api.rest_url', function ( string $rest_url ): string {

	return current_user_can( 'manage_options' ) ? $rest_url : '';
} );
```

## License

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
