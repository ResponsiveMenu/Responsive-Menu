<?php

namespace ResponsiveMenu\Filesystem;

class FileCreator {

  public function create($folder, $file_name, $content) {
    return $this->open_write_and_close($folder . '/' . $file_name, $content);
  }

  protected function open_write_and_close($file_name, $data) {
    if($file = fopen($file_name, 'w')):
      fwrite($file, $data);
      fclose($file);
    else:
      return false;
    endif;

    return true;
  }

}
