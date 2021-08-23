# Statamic Consent Manager

A Statamic 3 addon to manage consent for cookies 🍪.

**Features**

* Define consent groups _and_ scripts loaded dynamically only when consent for a group is given.
* Provides a simple javascript API to read and change consent for users.
* Consent is stored client-side only, making it possible to use the static cache.

## Installation

Run the following commands:

```
composer require gridonic/statamic-consent-manager
php artisan vendor:publish --tag=statamic-consent-manager-config
```

## Configuration

Edit the configuration file located in `config/statamic/consent_manager.php`:

```php
<?php

return [
    // Where to store the consent settings of any user (javascript).
    // Choose "local" for local storage or "session" for session storage.
    'storage' => 'local',

    // Consent groups managed by the addon. Feel free to change, remove or add your own groups.
    // Scripts are dynamically added to the DOM only if consent for their group is given.
    'groups' => [
        'necessary' => [
            'required' => true,
            'consented' => true,
            'scripts' => [
                [
                    // The full script tag to include in the page if consent is given.
                    'tag' => '<script>console.log(\'script dynamically loaded with consent manager\');</script>',
                    // Choose "head" or "body" to append the script to the page.
                    'appendTo' => 'head',
                ],
            ],
        ],
        'marketing' => [
            'required' => false,
            'consented' => false,
            'scripts' => [],
        ],
        'statistics' => [
            'required' => false,
            'consented' => false,
            'scripts' => [],
        ]
    ],
];
```
## Usage

1. Define your consent groups and their scripts in the configuration. 
2. Add the `{{ consent_manager }}` tag in the `head` of your layout.

That's it! 🥳 What's left up to you is to design a nice cookie banner and
modify consent data by using the javascript API.

### Rendering a cookie banner

The addon provides the tag `{{ consent_manager:groups }}` which can be used to loop over
consent groups in Antlers.

```
{{ consent_manager:groups }}
    Group ID: {{ id }}
    Consented by default: {{ consented }}
    Required: {{ required }}  
{{ /consent_manager:groups }}
```

> ℹ️ To read of modify consent, you need to use the javascript API, because the data is stored client-side only.

### Javascript API

The addon exposes a `window.consentManager` object to read and write consent data.

| Method | Description |
| --- | --- |
| `getConsent(group: string): boolean` | Check if the user has consented to a group. |
| `setConsent(group: string, consent: boolean)` | Set or remove consent to a group. If consent is given, all scripts of the group are appended to the DOM. |
| `isRequired(group: string): boolean` | Check if a group is required. |
| `getGroups(): Array` | Get all consent groups. |
| `getGroup(group: string): Object` | Get information about a single group. |
