<?php

namespace ResponsiveMenu\Database\Migrations;
use ResponsiveMenu\Collections\OptionsCollection;

class Migrate {

    public function migrate(OptionsCollection $options) {
        if(isset($this->migrations))
            foreach($this->migrations as $copy_to => $copy_from)
                $options[$copy_to] = $options[$copy_from];

        if(isset($this->migration_scripts))
            foreach($this->migration_scripts as $script)
                $options = $this->$script($options);

        return $options;
    }

    public function getOldVersion() {
        $v = $this->getVersionArray();
        return $v[0] . '.' . $v[1] . '.' . $v[2];
    }

    public function getNewVersion() {
        $v = $this->getVersionArray();
        return $v[3] . '.' . $v[4] . '.' . $v[5];
    }

    private function getVersionArray() {
        $versions = str_replace('ResponsiveMenu\Database\Migrations\Migrate_', '', get_class($this));
        return explode('_', $versions);
    }

}