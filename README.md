# 8fold Events for PHP

Events displays a grid-view that mimics a calendar for years and months.

Events is designed to respond as quickly and directly as possible to a single request.

## Install

```bash
composer require 8fold/php-events
```

## Usage

We have divided 8fold Events between content (Data) and user interface (UI).

This means you can develope your own UI and access the data using the provided flat-file objects to query and retrieve the data; using the `Events` class.

You can also use the grid-based UI via the `Grid` class and provide your own CSS and JavaScript.

Or, you can use the CSS and JavaScript provided in the `dist` folder. The implmentations used to generate both are avaialble in the `sass` and `javascript` folders, respectively.

### Content only

```php
Events::fold("/path/to/content-root");
```

For optimal performance, start with the `Events` class, which acts as a container for cached instances representing files. These files are [lazy-loaded](https://en.wikipedia.org/wiki/Lazy_loading).

### UI (with content)

You can display a month (common) or year view.

The `unfold` method queries and renders the content.

For the month view:

```php
Grid::forMonth("/events/{year}/{month}")->unfold();
```

Or, for the year view:

```php
Grid::forYear("/events/{year}")->unfold();
```

For the base route or page (`/events`), we recommend you redirect your users to either a month or year view. The `Events` class has convenience methods to find the next or previous month or year with events; alternatively, and the fastest method, would be to redirect the use to the current month for the current year or just the current year.

The month view will provide a link to the next and previous months with events, regardless of year. The year view, will display each month with the number of events during that month; the view will also provide links to the next or previous years with events along.

## Content folder structure

8fold Events depends on a specific folder structure for the data side. The root folder can be anywhere you want, as long as PHP can reach that folder:

```bash
.
└── root/
    └── {year}/
        └── {two-digit month}/
            ├── {two-digit day}.event
            └── {two-digit day}_{1-n}.event
```

Production example:

```bash
.
└── root/
    └── 2020/
        └── 01/
            ├── 01.event
            ├── 02.event
            ├── 02_2.event
            ├── 03_1.event
            └── 03_2.event
```

Each day is represented by one or more `.event` files, which are plain-text, markdown files. For days with more than one event, you can add a suffix to the file name starting with an underscore and the order in which they should appear in the modal popover; having a suffix of `_1` is optional.
