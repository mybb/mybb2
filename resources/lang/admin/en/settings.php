<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

return [
    'yes'            => 'Yes',
    'no'             => 'No',
    'board_settings' => 'Board Settings',
    'extensions'     => 'Extensions settings',
    'role'           => [
        'none'   => 'None',
        'select' => 'Select roles',
        'all'    => 'All roles',
    ],
    'forum'          => [
        'none'   => 'None',
        'select' => 'Select forums',
        'all'    => 'All forums',
    ],

    // settings
    'general'        => [
        '__group'    => [
            'title' => 'General forum settings',
            'desc'  => 'This section contains various settings such as your board name and url, as well as your website name and url.',
        ],
        'board_desc' => [
            'title' => 'Board description',
            'desc'  => 'setting description',
        ],
        'board_name' => [
            'title' => 'Board name',
            'desc'  => 'The name of your community. We recommend that it is not over 75 characters.',
        ],
        'site_name'  => [
            'title' => 'Site name',
            'desc'  => 'setting description',
        ],
        'site_url'   => [
            'title' => 'Site url',
            'desc'  => 'setting description',
        ],
    ],

    'likes' => [
        '__group' => [
            'title' => 'Like system',
            'desc' => 'Settings for likes'
        ],
        'per_page' => [
            'title' => 'Likes per page',
            'desc'  => 'The number of likes to show per page'
        ]
    ],

    'captcha' => [
        '__group' => [
            'title' => 'Captcha system',
            'desc' => 'Settings for captcha'
        ],
    ],

    'post' => [
        '__group' => [
            'title' => 'Posts',
            'desc' => 'Settings for posts'
        ],
    ],

    'conversations' => [
        '__group' => [
            'title' => 'Conversations system',
            'desc' => 'Settings for user conversations'
        ],
    ],

    'wio' => [
        '__group' => [
            'title' => 'Who is online',
            'desc'  => 'Various settings regarding the Who is Online functionality.',
        ],
        'minutes' => [
            'title' => 'Cut-off Time (mins)',
            'desc'  => 'The number of minutes before a user is marked offline.',
        ],
        'refresh' => [
            'title' => 'Refresh Who\'s online page Time (mins)',
            'desc'  => 'The number of minutes before the "Who\'s online" page refreshes. 0 for disabled.',
        ],
    ],

    'memberlist' => [
        '__group'  => [
            'title' => 'Member list',
            'desc'  => 'This section allows you to control various aspects of the board member listing, such as how many members to show per page, and which features to enable or disable.',
        ],
        'per_page' => [
            'title' => 'Members per page',
            'desc'  => 'The number of members to show per page on the member list.',
        ],
        'sort_by'  => [
            'title'    => 'Default sort field',
            'desc'     => 'Select the field that you want members to be sorted by default.',
            '__option' => [
                'created_at' => 'Registration date',
                'num_posts'  => 'Post count',
                'num_topics' => 'Topics count',
                'name'       => 'Username',
            ],
        ],
        'sort_dir' => [
            'title'    => 'Default sort order',
            'desc'     => 'Select the order that you want members to be sorted by default.
                Ascending: A-Z / beginning-end
                Descending: Z-A / end-beginning',
            '__option' => [
                'asc'  => 'Ascending',
                'desc' => 'Descending',
            ],
        ],
    ],

    'warnings' => [
        '__group'      => [
            'title' => 'Warning system',
            'desc'  => 'The warning system allows forum staff to warn users for rule violations. Here you can manage the settings that control the warning system.',
        ],
        'allow_custom' => [
            'title' => 'Allow custom warning types?',
            'desc'  => 'Allow a custom reason and amount of points to be specified by those with permissions to warn users.',
        ],
        'allow_zero'   => [
            'title' => 'Allow warnings with 0 points?',
            'desc'  => 'Allow to creating warnings with 0 warning points.',
        ],
        'max_points'   => [
            'title' => 'Maximum warning points',
            'desc'  => 'The maximum warning points that can be given to a user before it is considered a warning level of 100%.',
        ],
    ],
];
