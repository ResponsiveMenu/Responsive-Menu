<?php

namespace ResponsiveMenu\Database;
use ResponsiveMenu\Options\Options as Options;
use ResponsiveMenu\Database\Database as Database;

class Migration{

	protected $db;

  const VERSION_VAR = 'RMVer';

	public function __construct(Database $db, $default_options)
	{
		$this->db = $db;
    $this->defaults = $default_options;
	}

	protected function addNewOptions()
	{
    # If DB is empty we need to fill it up!
    if(empty($options = $this->db->all())):
      foreach($this->defaults as $name => $value)
        $this->db->insert(array('name' => $name, 'value' => $value));
    # Otherwise we only add new options
    else:
      foreach($options as $converted)
        $current[$converted->name] = $converted->value;
      $final = array_diff_key($this->defaults, $current);
      if(is_array($final)):
  		    foreach($final as $name => $value)
  			     $this->db->insert(array('name' => $name, 'value' => $value));
      endif;
    endif;
	}

	protected function tidyUpOptions()
	{
		$current = array_map(function($a) { return $a->name; }, $this->db->all());
    foreach(array_diff($current, array_keys($this->defaults)) as $to_delete)
      $this->db->delete(array('name' => $to_delete));
	}

	public function setup()
	{
    # Create the database table if it doesn't exist
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->db->table}` (
				  `name` varchar(255) NOT NULL,
				  `value` varchar(5000) DEFAULT NULL,
				  `created_at` datetime NOT NULL,
				  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (name)
				) {$this->db->db->get_charset_collate()}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	}

	public function synchronise()
	{
    # First Thing we need to do is migrate any old options
    if(!$this->isVersion3($this->getOldVersion())):
      $this->migrateVersion2Options();
    endif;

    # Now we can add any new options
		$this->addNewOptions();

    # Finally delete any that are no longer used
    $this->tidyUpOptions();

    # Perform any version specific updates
		if($this->needsUpdate($this->getOldVersion(), $this->getCurrentVersion())):
			$this->updateVersion();
		endif;
	}

	protected function needsUpdate($current_version, $old_version)
	{
		return version_compare($current_version, $old_version, '<');
	}

	protected function getOldVersion()
	{
		return get_option(self::VERSION_VAR);
	}

	protected function updateVersion()
	{
		update_option(self::VERSION_VAR, $this->getCurrentVersion());
	}

  protected function isVersion3($version)
  {
    return substr($version, 0, 1) == 3;
  }

  protected function migrateVersion2Options()
  {

  }

  public function getCurrentVersion()
  {
    $plugin_data = get_plugin_data(dirname(dirname(dirname(dirname(__FILE__)))) . '/responsive-menu-3.php', false, false);
    return $plugin_data['Version'];
  }

}
