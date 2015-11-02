# Cxense Insight PHP SDK

## Installation via composer

In `composer.json` Add the SDK to your require:

```json
"require": {
    "deseretdigital/cxense-insight-php-sdk": "~1.0"
}
```

Add the SDK repository:

```json
 "repositories": [
    {
        "type": "vcs",
        "url":  "git@github.com:deseretdigital/cxense-insight-php-sdk.git"
    }
]
```
Run `composer install`

If you don't know composer you can read up on it here https://getcomposer.org/

## Usage

```php
include __DIR__ . '/vendor/autoload.php';

use CxInsightSDK\Traffic;

$traffic = new Traffic(
    'test@example.com',
    'apikey',
    [
        'siteId'
    ]
);

// Get data for one week's time
$options = [
    'start' => strtotime('-1 week'),
    'stop' => time(),
    'fields' => [
        'events',
        'uniqueUsers',
        'urls',
        'activeTime',
        'sessionStarts',
        'sessionStops',
        'sessionBounces'
    ],
    'filters' => [
        [
            'type' => 'event',
            'group' => 'url',
            'items'  => ['http://example.com']
        ]
    ]
];

$data = $traffic->getData($options);
```

For more information on options/parameters that can be sent to the cxense API see the [Cxense API Wiki](https://wiki.cxense.com/display/cust/Cxense+Insight+API).

**NOTE:** Currently this only supports for the cxense API endpoints: `/traffic`, `/traffic/event`, `/traffic/custom`, `/dmp/traffic`, `/dmp/traffic/event`, `/dmp/traffic/custom`
