<?php

namespace ResponsiveMenu\Models;

interface Option {

	public function __construct($name, $value);
	public function getName();
	public function getValue();
	public function setValue($value);
	public function __toString();

}
