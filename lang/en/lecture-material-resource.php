<?php

return [
    'model_label' => 'Lecture Material',
    'model_label_plural' => 'Lecture Materials',

    'navigation' => [
        'group' => 'Learning Content',
    ],

    'form' => [
        'section' => 'Material Information',
        'lecture' => 'Belongs to Lecture',
        'name' => 'Material Name',
        'description' => 'Description',
        'video_links' => 'Video Links (Youtube, Vimeo, ...)',
        'video_links_key' => 'Platform Name (e.g., Youtube)',
        'video_links_value' => 'Link (URL)',
        'attachments' => 'Attachments (PDF, Word, ...)',
    ],

    'table' => [
        'name' => 'Material Name',
        'lecture' => 'Belongs to Lecture',
        'has_video' => 'Has Video',
        'uploader' => 'Uploader',
        'created_at' => 'Created At',
    ],
    'infolist' => [
        'section_main' => 'Details',
        'section_meta' => 'Metadata',
        'uploader' => 'Uploader',
        'created_at' => 'Created At',
        'attachments' => 'Attachments',
        'video_links' => 'Video Links',
    ],
];
