<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "srgssr"
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'SRGSSR Swiss online media service',
    'description' => 'Suisse media providers for srgssr.ch',
    'category' => 'misc',
    'author' => 'Daniel Huf',
    'author_email' => 'daniel.huf@sac-cas.ch',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.3',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0.0-10.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
