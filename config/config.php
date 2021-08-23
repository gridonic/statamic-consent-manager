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
