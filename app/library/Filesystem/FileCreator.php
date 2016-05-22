<?php

namespace ResponsiveMenu\Filesystem;

class FileCreator
{

  public function createCssFile($folder, $file_name, $css)
  {
    return $this->open_write_and_close($folder . '/' . $file_name, $css);
  }

  public function createJsFile($folder, $file_name, $js)
  {
    return $this->open_write_and_close($folder . '/' . $file_name, $js);
  }

  protected function open_write_and_close($file_name, $data)
  {
    if($file = fopen($file_name, 'w')):
      fwrite($file, $data);
      fclose($file);
    else:
      return false;
    endif;

    return true;
  }

}
