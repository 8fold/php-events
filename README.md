# 8fold Events

8fold Events displays grid-view that mimics a calendar for years and months.

## Install

```bash
composer require 8fold/php-events
```

## Usage

We have divided 8fold Events between content and user interface.

If you would like to develop your own user interface, you can get the required information and content by using the `Events` class.

If you would like to use the provided user interface, you can get the views for years and months by using the `Grid` class.

```php
// Content only
Events::init("/path/to/content-root");

// Year with default URL prefix
Grid::forYear("/path/to/content-root/year")
	->render();

// Year with custom URL prefix
Grid::forYear("/path/to/content-root/year")
	->uriPrefix("/uri/path/for/links")->render();

// Month with default URL prefix
Grid::forMonth("/path/to/content-root/year/month")
	->render();
```

The `render` method returns a compiled HTML string using [8fold Markup](https://github.com/8fold/php-markup) and [8fold Shoop](https://github.com/8fold/php-shoop); therefore, the surrounding elements are up to you.

We also provide styling and a javascript file for interactivity in the `dist` folder of the project.

## Content folder structure

8fold Events depends on a specific folder structure for the data side. The root folder can be anywhere you want, as long as PHP can reach that folder:

```
- /root
  - /[year]
    - /[month]
      [day].event
      [day]_[count].event
```

Production example:

```
- /root
	- /2020
		- /01
			01.event
			02.event
			02_2.event
			03_1.event
			03_2.event
```

Each event for a `day` is a separate low-level file.

Each event name begins with the two-digit `day`.

When there are multiple events on a `day`, the `day` should be suffixed by the `order` the event is in the `day`; the `day` and `order` should be separated by an underscore (`_`).

When there are multiple events on a `day`, the first event can optionally be suffixed with `1`.
