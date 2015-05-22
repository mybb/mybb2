<?php
/**
 * Date functions for twig
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

use Jenssegers\Date\Date as TransDate;
use MyBB\Core\Exceptions\DateInvalidObjectException;
use MyBB\Settings\Store;

class ParseDateHelper
{
	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @param Store $settings
	 */
	public function __construct(Store $settings)
	{
		$this->settings = $settings;
	}

	/**
	 * @param int|string|\DateTime|TransDate $date
	 * @param bool                           $showTime
	 * @param string                         $format
	 *
	 * @return string
	 */
	public function formatDate($date = null, $showTime = true, $format = null)
	{
		// Get the user format if none was specified
		if ($format == null) {
			$format = $this->settings->get('user.date_format', 'default');
			if ($format == 'default') {
				$format = trans('general.dateformat');
			}

			// Show time?
			if ($showTime) {
				$timeFormat = $this->settings->get('user.time_format', 'default');
				if ($timeFormat == 'default') {
					$timeFormat = trans('general.timeformat');
				}

				$time = 'H:i';
				if ($timeFormat == 12) {
					$time = 'h:i A';
				}

				$format .= ' ' . $this->at() . ' ' . $time;
			}
		} elseif ($format == 'default') {
			$format = trans('general.dateformat');

			// Show time?
			if ($showTime) {
				$timeFormat = trans('general.timeformat');

				$time = 'H:i';
				if ($timeFormat == 12) {
					$time = 'h:i A';
				}

				$format .= ' ' . $this->at() . ' ' . $time;
			}
		}

		return $this->getDateObject($date)->format($format);
	}

	/**
	 * @param int|string|\DateTime|TransDate $date
	 *
	 * @return string
	 */
	public function humanDate($date = null)
	{
		$date = $this->getDateObject($date);

		// Should be some kind of dynamic - however displaying something like "3 years ago" may not be usefull
		if ($date->diffInDays() > 7) {
			return $this->formatDate($date);
		}

		// Everything below 5 is "loading time"
		if ($date->diffInSeconds() < 5) {
			return trans('general.now');
		}

		return $date->diffForHumans();
	}

	/**
	 * @param int|string|\DateTime|TransDate $date
	 * @param string                         $showFormat
	 * @param string                         $attributeFormat
	 *
	 * @return string
	 */
	public function generateTime($date = null, $showFormat = null, $attributeFormat = null)
	{
		$date = $this->getDateObject($date);

		if ($showFormat != null) {
			$showTime = $this->formatDate($date, true, $showFormat);
		} else {
			$showTime = $this->humanDate($date);
		}
		$attributeTime = $this->formatDate($date, true, $attributeFormat);
		$dateTimeFormat = $this->formatDate($date, true, 'c');

		return "<time datetime=\"{$dateTimeFormat}\" title=\"{$attributeTime}\">{$showTime}</time>";
	}

	/**
	 * @param string                         $url
	 * @param int|string|\DateTime|TransDate $date
	 * @param string                         $showFormat
	 * @param string                         $attributeFormat
	 *
	 * @return string
	 */
	public function postDateLink($url, $date = null, $showFormat = null, $attributeFormat = null)
	{
		$date = $this->getDateObject($date);

		if ($showFormat != null) {
			$showTime = $this->formatDate($date, true, $showFormat);
		} else {
			$showTime = $this->humanDate($date);
		}
		$attributeTime = $this->formatDate($date, true, $attributeFormat);

		return "<a href=\"{$url}\" class=\"post__date\" title=\"{$attributeTime}\">{$showTime}</a>";
	}

	/**
	 * @param int|string|\DateTime|TransDate $date
	 *
	 * @return TransDate
	 *
	 * @throws DateInvalidObjectException
	 */
	private function getDateObject($date)
	{
		// We've already a valid date object. Don't set the timezone, it may get messy otherwise
		if ($date instanceof TransDate) {
			return $date;
		}

		if($date instanceof \DateTime) {
			$date = $date->format('d.m.Y H:i:s');
		}

		// If it's a valid date format or a DateTime object we can simply call the constructor
		if (is_int($date) || @strtotime($date) !== false || $date == null) {
			$date = new TransDate($date);
		} else {
			throw new DateInvalidObjectException;
		}

		// Figure out our timezone
		$timezone = $this->settings->get('user.timezone', 'default');
		if ($timezone == 'default') {
			$timezone = trans('general.timezone');
		}

		return $date->setTimezone($timezone);
	}

	/**
	 * @return string
	 */
	private function at()
	{
		$at = str_split(trans('general.at'));
		$escapedAt = '';
		foreach ($at as $a) {
			$escapedAt .= "\\$a";
		}

		return $escapedAt;
	}
}
