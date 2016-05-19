<?php

namespace ResponsiveMenu\Formatters;

class Tabs
{
	public static function render($options)
	{
		foreach(self::getTabs($options) as $key => $val)
			echo '<div class="tab" id="tab_' . $key . '">' . $val . '</div>';

	}

	public static function getTabs($options)
	{
		$final = array();
		foreach($options as $option) :
			if($option->getPosition()) :
				$item = explode('.',$option->getPosition());
				$final[$item[1]] = ucwords(str_replace('_', ' ', $item[1]));
			endif;
		endforeach;
		return array_unique(array_filter($final));
	}

	public static function getTabOptions($tab_name, $options)
	{
		$final = array();
		foreach($options as $option) :
			if($option->getPosition()) :
				$item = explode('.',$option->getPosition());
				if($item[1] == $tab_name)
					$final[] = $option;
			endif;
		endforeach;
		return $final;
	}

}
