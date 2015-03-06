<?php

return [
	'unknown' => 'Unknown Location',

	'members' => [
		'index' => 'Views <a href=":url">Memberlist</a>',
		'online' => 'Views <a href=":url">Who\'s online</a>',
	],
	'forum' => [
		'index' => 'Views <a href=":url">Index</a>',
	],
	'forums' => [
		'all' => 'Views <a href=":url">all Forums</a>',
		'show' => 'Views Forum <a href=":url">:forum</a>',
	],
	'account' => [
		'index' => 'Views his <a href=":url">dashboard</a>',
		'profile' => 'Edits his <a href=":url">profile</a>',
		'username' => 'Changes his <a href=":url">username</a>',
		'email' => 'Changes his <a href=":url">email</a>',
		'password' => 'Changes his <a href=":url">password</a>',
		'avatar' => [
			'index' => 'Changes his <a href=":url">avatar</a>',
			'remove' => 'Removes his avatar',
		],
		'notifications' => 'Manages his <a href=":url">notifications</a>',
		'following' => 'Changes his <a href=":url">following settings</a>',
		'buddies' => 'Manages his <a href=":url">buddies</a>',
		'preferences' => 'Changes his <a href=":url">preferences</a>',
		'privacy' => 'Changes his <a href=":url">privacy settings</a>',
		'drafts' => 'Manages his <a href=":url">drafts</a>',
	],
	'search' => [
		'index' => '<a href=":url">Searchs</a>',
		'post' => 'Views <a href=":url">Search Results</a>',
	],
	'topics' => [
		'show' => 'Views Topic <a href=":url">:topic</a>',
		'reply' => [
			'index' => 'Replies to Topic <a href=":url">:topic</a>',
			'post' => 'Posts a reply to <a href=":url">:topic</a>',
		],
		'edit' => 'Edits a post in <a href=":url">:topic</a>',
		'delete' => 'Deletes a post in <a href=":url">:topic</a>',
		'restore' => 'Restores a post in <a href=":url">:topic</a>',
		'create' => [
			'index' => 'Writes a new topic in <a href=":url">:forum</a>',
			'post' => 'Posts a new topic in <a href=":url">:forum</a>',
		]
	],
];