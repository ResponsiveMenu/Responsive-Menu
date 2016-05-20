<?php

namespace ResponsiveMenu\Formatters;

class Tabs
{
	public static function render($options)
	{
    $tabs = self::getTabs($options);
		foreach($tabs as $key => $val):
      $is_active = $val == reset($tabs) ? ' active_tab' : '';
			echo '<div class="tab' . $is_active . '" id="tab_' . $key . '">' . $val . '</div>';
    endforeach;
	}

	public static function getTabs($options)
	{
		$final = array();
		foreach($options as $option):
			if($option->getPosition()):
				$item = explode('.',$option->getPosition());
				$final[$item[0]] = ucwords(str_replace('_', ' ', $item[0]));
			endif;
		endforeach;
		return array_unique(array_filter($final));
	}

	public static function getSubTabs($tab, $options)
	{
    $final = array();
		foreach($options as $option):
			if($option->getPosition()):
				$item = explode('.',$option->getPosition());
				$final[] = $item[0] == $tab ? $option->getPosition() : null;
			endif;
		endforeach;
		return array_unique(array_filter($final));
	}

	public static function getTabOptions($tab_name, $options)
	{
		$final = array();
		foreach($options as $option) :
			if($option->getPosition()) :
				if($tab_name == $option->getPosition())
					$final[] = $option;
			endif;
		endforeach;
		return $final;
	}

}
