<?php
namespace MyBB\Core\Twig\Extensions;

use Jenssegers\Date\Date as TransDate;
use MyBB\Settings\Store;

class ParseDateHelper
{
	private $settings;
	public function __construct(Store $settings)
	{
		$this->settings = $settings;
	}

	public function formatDate($date = null, $showTime = true, $format = null)
	{
		// Get the user format if none was specified
		if($format == null)
		{
			$format = $this->settings->get('user.date_format', 'default');
			if($format == 'default')
			{
				$format = trans('general.dateformat');
			}

			// Show time?
			if($showTime)
			{
				$timeFormat = $this->settings->get('user.time_format', 'default');
				if($timeFormat == 'default')
				{
					$timeFormat = trans('general.timeformat');
				}

				$format .= ' '.$this->at().' '.$timeFormat;
			}
		}
		elseif($format == 'default')
		{
			$format = trans('general.dateformat');

			// Show time?
			if($showTime)
			{
				$timeFormat = trans('general.timeformat');

				$format .= ' '.$this->at().' '.$timeFormat;
			}
		}

		return $this->getDateObject($date)->format($format);
	}

	public function humanDate($date = null)
	{
		$date = $this->getDateObject($date);

		// Should be some kind of dynamic - however displaying something like "3 years ago" may not be usefull
		if($date->diffInDays() > 7)
		{
			return $this->formatDate($date);
		}

		// Everything below 5 is "loading time"
		if($date->diffInSeconds() < 5)
		{
			return trans('general.now');
		}

		return $date->diffForHumans();
	}

	public function generateTime($date = null, $showFormat = null, $attributeFormat = null)
	{
		$date = $this->getDateObject($date);

		if($showFormat != null)
		{
			$showTime = $this->formatDate($date, true, $showFormat);
		}
		else
		{
			$showTime = $this->humanDate($date);
		}
		$attributeTime = $this->formatDate($date, true, $attributeFormat);
		$dateTimeFormat = $this->formatDate($date, true, 'c');

		return "<time datetime=\"{$dateTimeFormat}\" title=\"{$attributeTime}\">{$showTime}</time>";
	}

	private function getDateObject($date)
	{
		// We've already a valid date object. Don't set the timezone, it may get messy otherwise
		if($date instanceof TransDate)
		{
			return $date;
		}

		// If it's a valid date format or a DateTime object we can simply call the constructor
		if(is_int($date) || @strtotime($date) !== false || $date == null || $date instanceof \DateTime)
		{
			$date = new TransDate($date);
		}
		else
		{
			throw new \Exception('$date needs to be either an integer (timestamp) or an instance of either DateTime or Date');
		}

		// Figure out our timezone
		$timezone = $this->settings->get('user.timezone', 'default');
		if($timezone == 'default')
		{
			$timezone = trans('general.timezone');
		}

		return $date->setTimezone($timezone);
	}

	private function at()
	{
		$at = str_split(trans('general.at'));
		$escapedAt = '';
		foreach($at as $a)
		{
			$escapedAt .= "\\$a";
		}
		return $escapedAt;
	}
}
