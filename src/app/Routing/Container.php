<?php

namespace ResponsiveMenu\Routing;

class Container implements \ArrayAccess
{
    private $values = [];
    private $raw = [];
    private $keys = [];

    public function offsetSet($id, $value) {
      $this->values[$id] = $value;
      $this->keys[$id] = true;
    }

    public function offsetGet($id) {

      if(!isset($this->keys[$id])) {
        throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
      }

      if(isset($this->raw[$id]) || !is_object($this->values[$id]) || !method_exists($this->values[$id], '__invoke')) {
        return $this->values[$id];
      }

      $raw = $this->values[$id];
      $val = $this->values[$id] = $raw($this);
      $this->raw[$id] = $this->values[$id];

      return $this->values[$id];
    }

    public function offsetExists($id) {
      return isset($this->keys[$id]);
    }

    public function offsetUnset($id) {
      if(isset($this->keys[$id])) {
        unset($this->values[$id], $this->raw[$id], $this->keys[$id]);
      }
    }

    public function keys() {
        return array_keys($this->values);
    }

}
