<?php

namespace ResponsiveMenu\Database;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;
use ResponsiveMenu\Database\Database as Database;
use ResponsiveMenu\Services\OptionService as OptionService;

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

  /*
  Can't be tested :-( */
	public function setup() {
    # Create the database table if it doesn't exist
    if(!$this->isVersion3()):
      $sql = "CREATE TABLE " . $this->db->getPrefix() . self::$table . " (
      				  name varchar(50) NOT NULL,
      				  value varchar(5000) DEFAULT NULL,
      				  created_at datetime NOT NULL,
      				  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (name)
      				) " . $this->db->getCharset() . ";";
  		require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta($sql);
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
		update_option(self::$version_var, $this->current_version);
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
      'menu_to_use' => $old_options['RM'] ? $old_options['RM'] : '',
      'breakpoint' => $old_options['RMBreak'] ? $old_options['RMBreak'] : '',
      'menu_depth' => $old_options['RMDepth'] ? $old_options['RMDepth'] : '',
      'button_top' => $old_options['RMTop'] ? $old_options['RMTop'] : '',
      'button_distance_from_side' => $old_options['RMRight'] ? $old_options['RMRight'] : '',
      'menu_to_hide' => $old_options['RMCss'] ? $old_options['RMCss'] : '',
      'menu_title' => $old_options['RMTitle'] ? $old_options['RMTitle'] : '',
      'button_line_colour' => $old_options['RMLineCol'] ? $old_options['RMLineCol'] : '',
      'button_background_colour' => $old_options['RMClickBkg'] ? $old_options['RMClickBkg'] : '',
      'button_title' => $old_options['RMClickTitle'] ? $old_options['RMClickTitle'] : '',
      'button_transparent_background' => $old_options['RMBkgTran'] ? 'on' : '',
      'menu_font' => $old_options['RMFont'] ? $old_options['RMFont'] : '',
      'button_position_type' => $old_options['RMPos'] ? 'fixed' : '',
      'menu_title_image' => $old_options['RMImage'] ? $old_options['RMImage'] : '',
      'menu_width' => $old_options['RMWidth'] ? $old_options['RMWidth'] : '',
      'menu_item_background_colour' => $old_options['RMBkg'] ? $old_options['RMBkg'] : '',
      'menu_background_colour' => $old_options['RMBkg'] ? $old_options['RMBkg'] : '',
      'menu_sub_arrow_background_colour' => $old_options['RMBkg'] ? $old_options['RMBkg'] : '',
      'menu_item_background_hover_colour' => $old_options['RMBkgHov'] ? $old_options['RMBkgHov'] : '',
      'menu_sub_arrow_background_hover_colour' => $old_options['RMBkgHov'] ? $old_options['RMBkgHov'] : '',
      'menu_title_colour' => $old_options['RMTitleCol'] ? $old_options['RMTitleCol'] : '',
      'menu_link_colour' => $old_options['RMTextCol'] ? $old_options['RMTextCol'] : '',
      'menu_sub_arrow_shape_colour' => $old_options['RMTextCol'] ? $old_options['RMTextCol'] : '',
      'menu_item_border_colour' => $old_options['RMBorCol'] ? $old_options['RMBorCol'] : '',
      'menu_item_border_colour_hover' => $old_options['RMBorCol'] ? $old_options['RMBorCol'] : '',
      'menu_sub_arrow_border_colour' => $old_options['RMBorCol'] ? $old_options['RMBorCol'] : '',
      'menu_sub_arrow_border_hover_colour' => $old_options['RMBorCol'] ? $old_options['RMBorCol'] : '',
      'menu_link_hover_colour' => $old_options['RMTextColHov'] ? $old_options['RMTextColHov'] : '',
      'menu_sub_arrow_shape_hover_colour' => $old_options['RMTextColHov'] ? $old_options['RMTextColHov'] : '',
      'menu_title_hover_colour' => $old_options['RMTitleColHov'] ? $old_options['RMTitleColHov'] : '',
      'animation_type' => $old_options['RMAnim'] == 'push' ? 'push' : '',
      'page_wrapper' => $old_options['RMPushCSS'] ? $old_options['RMPushCSS'] : '',
      'menu_title_background_colour' => $old_options['RMTitleBkg'] ? $old_options['RMTitleBkg'] : '',
      'menu_title_background_hover_colour' => $old_options['RMTitleBkg'] ? $old_options['RMTitleBkg'] : '',
      'menu_font_size' => $old_options['RMFontSize'] ? $old_options['RMFontSize'] : '',
      'menu_title_font_size' => $old_options['RMTitleSize'] ? $old_options['RMTitleSize'] : '',
      'button_font_size' => $old_options['RMBtnSize'] ? $old_options['RMBtnSize'] : '',
      'menu_current_item_background_colour' => $old_options['RMCurBkg'] ? $old_options['RMCurBkg'] : '',
      'menu_current_link_colour' => $old_options['RMCurCol'] ? $old_options['RMCurCol'] : '',
      'animation_speed' => $old_options['RMAnimSpd'] ? $old_options['RMAnimSpd'] : '',
      'transition_speed' => $old_options['RMTranSpd'] ? $old_options['RMTranSpd'] : '',
      'menu_text_alignment' => $old_options['RMTxtAlign'] ? $old_options['RMTxtAlign'] : '',
      'auto_expand_all_submenus' => $old_options['RMExpand'] ? 'on' : '',
      'menu_links_height' => $old_options['RMLinkHeight'] ? $old_options['RMLinkHeight'] + 24 : '',
      'submenu_arrow_height' => $old_options['RMLinkHeight'] ? $old_options['RMLinkHeight'] + 24 : '',
      'submenu_arrow_width' => $old_options['RMLinkHeight'] ? $old_options['RMLinkHeight'] + 24 : '',
      'external_files' => $old_options['RMExternal'] ? 'on' : '',
      'menu_appear_from' => $old_options['RMSide'] ? $old_options['RMSide'] : '',
      'scripts_in_footer' => $old_options['RMFooter'] ? 'on' : '',
      'button_image' => $old_options['RMClickImg'] ? $old_options['RMClickImg'] : '',
      'minify_scripts' => $old_options['RMMinify'] ? 'on' : '',
      'menu_close_on_link_click' => $old_options['RMClickClose'] ? 'on' : '',
      'menu_minimum_width' => $old_options['RMMinWidth'] ? $old_options['RMMinWidth'] : '',
      'menu_maximum_width' => $old_options['RMMaxWidth'] ? $old_options['RMMaxWidth'] : '',
      'auto_expand_current_submenus' => $old_options['RMExpandPar'] ? 'on' : '',
      'menu_item_click_to_trigger_submenu' => $old_options['RMIgnParCli'] ? 'on' : '',
      'menu_close_on_body_click' => $old_options['RMCliToClo'] ? 'on' : '',
      'menu_title_link' => $old_options['RMTitleLink'] ? $old_options['RMTitleLink'] : '',
      'menu_additional_content' => $old_options['RMHtml'] ? $old_options['RMHtml'] : '',
      'shortcode' => $old_options['RMShortcode'] ? 'on' : '',
      'button_line_height' => $old_options['RMLineHeight'] ? $old_options['RMLineHeight'] : '',
      'button_line_width' => $old_options['RMLineWidth'] ? $old_options['RMLineWidth'] : '',
      'button_line_margin' => $old_options['RMLineMargin'] ? $old_options['RMLineMargin'] : '',
      'button_image_when_clicked' => $old_options['RMClickImgClicked'] ? $old_options['RMClickImgClicked'] : '',
      'accordion_animation' => $old_options['RMAccordion'] ? 'on' : '',
      'active_arrow_shape' => $old_options['RMArShpA'] ? json_decode($old_options['RMArShpA']) : '',
      'inactive_arrow_shape' => $old_options['RMArShpI'] ? json_decode($old_options['RMArShpI']) : '',
      'active_arrow_image' => $old_options['RMArImgA'] ? $old_options['RMArImgA'] : '',
      'inactive_arrow_image' => $old_options['RMArImgI'] ? $old_options['RMArImgI'] : '',
      'button_push_with_animation' => $old_options['RMPushBtn'] ? 'on' : '',
      'menu_current_item_background_hover_colour' => $old_options['RMCurBkgHov'] ? $old_options['RMCurBkgHov'] : '',
      'menu_current_link_hover_colour' => $old_options['RMCurColHov'] ? $old_options['RMCurColHov'] : '',
      'custom_walker' => $old_options['RMWalker'] ? $old_options['RMWalker'] : '',
      'button_left_or_right' => $old_options['RMLoc'] ? $old_options['RMLoc'] : '',
      'theme_location_menu' => $old_options['RMThemeLocation'] ? $old_options['RMThemeLocation'] : '',
      'button_title_position' => $old_options['RMClickTitlePos'] ? $old_options['RMClickTitlePos'] : '',
    ];

    $to_save = [];

    foreach(array_filter($new_options) as $key => $val)
      $to_save[$key] = $val;

    return $to_save;
  }

}
