<?php
/**
 * Setting configuration.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/settings
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

return [
	/**
	 * The type of store to use to load settings from.
	 */
	'store' => 'database',
	/**
	 * The name of the database table containing the settings.
	 */
	'settings_table' => 'settings',
	/**
	 * The name of the database table containing the setting values.
	 */
	'setting_values_table' => 'setting_values',
	/**
	 * Cache name for settings when caching core settings.
	 */
	'settings_cache_name' => 'mybb.core.settings',
];
