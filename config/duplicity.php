<?php

return [
    'backup_directory' => env('DUPLICITY_BACKUP_DIRECTORY', './'),
    'backup_to_url' => env('DUPLICITY_BACKUP_TO_URL', 'file://./backup'),

    'restore_url' => env('DUPLICITY_RESTORE_URL', 'file://./backup'),
    'restore_to_directory' => env('DUPLICITY_RESTORE_TO_DIRECTORY', './restore'),

    'excludes' => [
        './backup',
        './restore',
    ],
];