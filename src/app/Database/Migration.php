<?php

namespace ResponsiveMenu\Database;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Database\Database;
use ResponsiveMenu\Services\OptionService;

class Migration {

	protected $db;

  protected $current_version;
  protected $old_version;
  protected $old_options;
  protected $defaults;

  protected static $table = 'responsive_menu';
  protected static $version_var = 'RMVer';

	public function __construct(Database $db, OptionService $service, $defaults, $current_version, $old_version, $old_options) {
    $this->db = $db;
	  $this->service = $service;
    $this->defaults = $defaults;
    $this->current_version = $current_version;
    $this->old_version = $old_version;
    $this->old_options = $old_options;
	}

	public function addNewOptions() {
    $options = $this->service->all();
    if($options->isEmpty())
      $this->service->createOptions($this->defaults);
    else
	    $this->service->createOptions($this->getNewOptions($options));
	}

	public function tidyUpOptions() {
    foreach($this->getOptionsToDelete() as $delete)
      $this->db->delete(self::$table, array('name' => $delete));
	}

  public function getNewOptions(OptionsCollection $options) {
    $current = [];
    foreach($options->all() as $converted)
      $current[$converted->getName()] = $converted->getValue();
    return array_diff_key($this->defaults, $current);
  }

	public function setup() {
    if(!$this->isVersion3()):
      $this->db->createTable(self::$table);
      $this->synchronise();
    endif;
	}

	public function synchronise() {

    # First Thing we need to do is migrate any old options
    if($this->old_options && !$this->isVersion3())
      $this->migrateVersion2Options();

    if($this->needsUpdate()):

      # Now we can add any new options
  		$this->addNewOptions();

      # Finally delete any that are no longer used
      $this->tidyUpOptions();

      # And Update Version
			$this->updateVersion();

    endif;

	}

	public function needsUpdate() {
		return version_compare($this->old_version, $this->current_version, '<');
	}

	protected function updateVersion() {
		$this->db->updateOption(self::$version_var, $this->current_version);
    $this->old_version = $this->current_version;
	}

  public function isVersion3() {
    return substr($this->old_version, 0, 1) == 3;
  }

  public function migrateVersion2Options() {
    $this->service->createOptions($this->getMigratedOptions());
    $this->addNewOptions();
    $this->updateVersion();
  }

  public function getOptionsToDelete() {
    return array_diff(
          array_map(function($a) { return $a->getName(); }, $this->service->all()->all()),
          array_keys($this->defaults)
        );
  }

  public function getMigratedOptions() {
    $old_options = $this->old_options;

    $new_options = [
      'menu_to_use' => isset($old_options['RM']) ? $old_options['RM'] : '',
      'breakpoint' => isset($old_options['RMBreak']) ? $old_options['RMBreak'] : '',
      'menu_depth' => isset($old_options['RMDepth']) ? $old_options['RMDepth'] : '',
      'button_top' => isset($old_options['RMTop']) ? $old_options['RMTop'] : '',
      'button_distance_from_side' => isset($old_options['RMRight']) ? $old_options['RMRight'] : '',
      'menu_to_hide' => isset($old_options['RMCss']) ? $old_options['RMCss'] : '',
      'menu_title' => isset($old_options['RMTitle']) ? $old_options['RMTitle'] : '',
      'button_line_colour' => isset($old_options['RMLineCol']) ? $old_options['RMLineCol'] : '',
      'button_background_colour' => isset($old_options['RMClickBkg']) ? $old_options['RMClickBkg'] : '',
      'button_title' => isset($old_options['RMClickTitle']) ? $old_options['RMClickTitle'] : '',
      'button_transparent_background' => isset($old_options['RMBkgTran']) ? 'on' : '',
      'menu_font' => isset($old_options['RMFont']) ? $old_options['RMFont'] : '',
      'button_position_type' => isset($old_options['RMPos']) ? 'fixed' : '',
      'menu_title_image' => isset($old_options['RMImage']) ? $old_options['RMImage'] : '',
      'menu_width' => isset($old_options['RMWidth']) ? $old_options['RMWidth'] : '',
      'menu_item_background_colour' => isset($old_options['RMBkg']) ? $old_options['RMBkg'] : '',
      'menu_background_colour' => isset($old_options['RMBkg']) ? $old_options['RMBkg'] : '',
      'menu_sub_arrow_background_colour' => isset($old_options['RMBkg']) ? $old_options['RMBkg'] : '',
      'menu_item_background_hover_colour' => isset($old_options['RMBkgHov']) ? $old_options['RMBkgHov'] : '',
      'menu_sub_arrow_background_hover_colour' => isset($old_options['RMBkgHov']) ? $old_options['RMBkgHov'] : '',
      'menu_title_colour' => isset($old_options['RMTitleCol']) ? $old_options['RMTitleCol'] : '',
      'menu_link_colour' => isset($old_options['RMTextCol']) ? $old_options['RMTextCol'] : '',
      'menu_sub_arrow_shape_colour' => isset($old_options['RMTextCol']) ? $old_options['RMTextCol'] : '',
      'menu_item_border_colour' => isset($old_options['RMBorCol']) ? $old_options['RMBorCol'] : '',
      'menu_item_border_colour_hover' => isset($old_options['RMBorCol']) ? $old_options['RMBorCol'] : '',
      'menu_sub_arrow_border_colour' => isset($old_options['RMBorCol']) ? $old_options['RMBorCol'] : '',
      'menu_sub_arrow_border_hover_colour' => isset($old_options['RMBorCol']) ? $old_options['RMBorCol'] : '',
      'menu_link_hover_colour' => isset($old_options['RMTextColHov']) ? $old_options['RMTextColHov'] : '',
      'menu_sub_arrow_shape_hover_colour' => isset($old_options['RMTextColHov']) ? $old_options['RMTextColHov'] : '',
      'menu_title_hover_colour' => isset($old_options['RMTitleColHov']) ? $old_options['RMTitleColHov'] : '',
      'animation_type' => isset($old_options['RMAnim']) && $old_options['RMAnim'] == 'push' ? 'push' : '',
      'page_wrapper' => isset($old_options['RMPushCSS']) ? $old_options['RMPushCSS'] : '',
      'menu_title_background_colour' => isset($old_options['RMTitleBkg']) ? $old_options['RMTitleBkg'] : '',
      'menu_title_background_hover_colour' => isset($old_options['RMTitleBkg']) ? $old_options['RMTitleBkg'] : '',
      'menu_font_size' => isset($old_options['RMFontSize']) ? $old_options['RMFontSize'] : '',
      'menu_title_font_size' => isset($old_options['RMTitleSize']) ? $old_options['RMTitleSize'] : '',
      'button_font_size' => isset($old_options['RMBtnSize']) ? $old_options['RMBtnSize'] : '',
      'menu_current_item_background_colour' => isset($old_options['RMCurBkg']) ? $old_options['RMCurBkg'] : '',
      'menu_current_link_colour' => isset($old_options['RMCurCol']) ? $old_options['RMCurCol'] : '',
      'animation_speed' => isset($old_options['RMAnimSpd']) ? $old_options['RMAnimSpd'] : '',
      'transition_speed' => isset($old_options['RMTranSpd']) ? $old_options['RMTranSpd'] : '',
      'menu_text_alignment' => isset($old_options['RMTxtAlign']) ? $old_options['RMTxtAlign'] : '',
      'auto_expand_all_submenus' => isset($old_options['RMExpand']) ? 'on' : '',
      'menu_links_height' => isset($old_options['RMLinkHeight']) ? $old_options['RMLinkHeight'] + 24 : '',
      'submenu_arrow_height' => isset($old_options['RMLinkHeight']) ? $old_options['RMLinkHeight'] + 24 : '',
      'submenu_arrow_width' => isset($old_options['RMLinkHeight']) ? $old_options['RMLinkHeight'] + 24 : '',
      'external_files' => isset($old_options['RMExternal']) ? 'on' : '',
      'menu_appear_from' => isset($old_options['RMSide']) ? $old_options['RMSide'] : '',
      'scripts_in_footer' => isset($old_options['RMFooter']) ? 'on' : '',
      'button_image' => isset($old_options['RMClickImg']) ? $old_options['RMClickImg'] : '',
      'minify_scripts' => isset($old_options['RMMinify']) ? 'on' : '',
      'menu_close_on_link_click' => isset($old_options['RMClickClose']) ? 'on' : '',
      'menu_minimum_width' => isset($old_options['RMMinWidth']) ? $old_options['RMMinWidth'] : '',
      'menu_maximum_width' => isset($old_options['RMMaxWidth']) ? $old_options['RMMaxWidth'] : '',
      'auto_expand_current_submenus' => isset($old_options['RMExpandPar']) ? 'on' : '',
      'menu_item_click_to_trigger_submenu' => isset($old_options['RMIgnParCli']) ? 'on' : '',
      'menu_close_on_body_click' => isset($old_options['RMCliToClo']) ? 'on' : '',
      'menu_title_link' => isset($old_options['RMTitleLink']) ? $old_options['RMTitleLink'] : '',
      'menu_additional_content' => isset($old_options['RMHtml']) ? $old_options['RMHtml'] : '',
      'shortcode' => isset($old_options['RMShortcode']) ? 'on' : '',
      'button_line_height' => isset($old_options['RMLineHeight']) ? $old_options['RMLineHeight'] : '',
      'button_line_width' => isset($old_options['RMLineWidth']) ? $old_options['RMLineWidth'] : '',
      'button_line_margin' => isset($old_options['RMLineMargin']) ? $old_options['RMLineMargin'] : '',
      'button_image_when_clicked' => isset($old_options['RMClickImgClicked']) ? $old_options['RMClickImgClicked'] : '',
      'accordion_animation' => isset($old_options['RMAccordion']) ? 'on' : '',
      'active_arrow_shape' => isset($old_options['RMArShpA']) ? json_decode($old_options['RMArShpA']) : '',
      'inactive_arrow_shape' => isset($old_options['RMArShpI']) ? json_decode($old_options['RMArShpI']) : '',
      'active_arrow_image' => isset($old_options['RMArImgA']) ? $old_options['RMArImgA'] : '',
      'inactive_arrow_image' => isset($old_options['RMArImgI']) ? $old_options['RMArImgI'] : '',
      'button_push_with_animation' => isset($old_options['RMPushBtn']) ? 'on' : '',
      'menu_current_item_background_hover_colour' => isset($old_options['RMCurBkgHov']) ? $old_options['RMCurBkgHov'] : '',
      'menu_current_link_hover_colour' => isset($old_options['RMCurColHov']) ? $old_options['RMCurColHov'] : '',
      'custom_walker' => isset($old_options['RMWalker']) ? $old_options['RMWalker'] : '',
      'button_left_or_right' => isset($old_options['RMLoc']) ? $old_options['RMLoc'] : '',
      'theme_location_menu' => isset($old_options['RMThemeLocation']) ? $old_options['RMThemeLocation'] : '',
      'button_title_position' => isset($old_options['RMClickTitlePos']) ? $old_options['RMClickTitlePos'] : '',
    ];

    $to_save = [];

    foreach(array_filter($new_options) as $key => $val)
      $to_save[$key] = $val;

    return $to_save;
  }

}
