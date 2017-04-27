<?php

namespace ResponsiveMenu\Database;
use ResponsiveMenu\Management\OptionManager;

class Migration {

    private $manager;
    private $old_version;
    private $new_version;
    private $defaults;

    public function __construct(OptionManager $manager, $old_version, $new_version, $defaults) {
        $this->manager = $manager;
        $this->old_version = $old_version;
        $this->new_version = $new_version;
        $this->defaults = $defaults;
    }

    public function needsTable() {
        return substr($this->old_version, 0, 1) < 3;
    }

    public function needsUpdate() {
        return version_compare($this->old_version, $this->new_version, '<');
    }

    public function addNewOptions() {
        return $this->manager->createOptions(array_diff_key($this->defaults, $this->manager->all()->toArray()));
    }

    public function tidyUpOptions() {
        return $this->manager->removeOptions(array_diff_key($this->manager->all()->toArray(), $this->defaults));
    }

    public function getMigrationClasses() {
        $migrations = [];
        if($this->old_version):
            foreach(glob(__DIR__ . '/Migrations/Migrate_*.php') as $file) {
                $class_name = 'ResponsiveMenu\Database\Migrations\\' . basename($file, '.php');
                $class = new $class_name;
                if(
                    version_compare($class->getOldVersion(), $this->new_version, '<') &&
                    version_compare($this->old_version, $class->getNewVersion(), '<')
                )
                    $migrations[$class->getOldVersion()] = $class;
            }

            uksort($migrations, 'version_compare');
        endif;
        return $migrations;
    }

}