<?php

namespace ResponsiveMenu\Filesystem;

class FileCreator {

  public function create($folder, $file_name, $content) {
    return $this->open_write_and_close($folder . '/' . $file_name, $content);
  }

  protected function open_write_and_close($file_name, $data) {
    try{
      $file = fopen($file_name, 'w');
      fwrite($file, $data);
      fclose($file);
      return true;
    } catch(\Exception $e) {
      return false;
    }
  }

}
