<?php

namespace ResponsiveMenu\Formatters;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Tabs
{
	public static function render(OptionsCollection $options)
	{
    $tabs = self::getTabs($options);
		foreach($tabs as $key => $val):
      $is_active = $val == reset($tabs) ? ' active_tab' : '';
			echo '<a class="tab page-title-action' . $is_active . '" id="tab_' . $key . '">' . $val . '</a>';
    endforeach;
	}

	public static function getTabs(OptionsCollection $options)
	{
		$final = array();
		foreach($options->all() as $option):
			if($option->getPosition()):
				$item = explode('.',$option->getPosition());
				$final[$item[0]] = ucwords(str_replace('_', ' ', $item[0]));
			endif;
		endforeach;
		return array_unique(array_filter($final));
	}

	public static function getSubTabs($tab, OptionsCollection $options)
	{
    $final = array();
		foreach($options->all() as $option):
			if($option->getPosition()):
				$item = explode('.',$option->getPosition());
				$final[] = $item[0] == $tab ? $option->getPosition() : null;
			endif;
		endforeach;
		return array_unique(array_filter($final));
	}

	public static function getTabOptions($tab_name, OptionsCollection $options)
	{
		$final = array();
		foreach($options->all() as $option) :
			if($option->getPosition()) :
				if($tab_name == $option->getPosition())
					$final[] = $option;
			endif;
		endforeach;
		return $final;
	}

}
