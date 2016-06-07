<?php

namespace ResponsiveMenu\Formatters;

class Text
{
	public static function underscoreToWord($text, $echo = true)
	{
		$words = ucwords(str_replace('_', ' ', $text));
		if($echo)
			echo $words;
		else
			return $words;
	}

}
