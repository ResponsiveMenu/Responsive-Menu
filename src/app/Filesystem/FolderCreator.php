<?php

namespace ResponsiveMenu\Filesystem;

class FolderCreator {

  public function create($folder) {
    return mkdir($folder);
  }

  public function exists($dir) {
    return is_dir($dir);
  }

}
