<?php

namespace ResponsiveMenu\Services;
use ResponsiveMenu\Repositories\OptionRepository;
use ResponsiveMenu\WPML\WPML;
use ResponsiveMenu\Factories\AdminSaveFactory;
use ResponsiveMenu\Factories\OptionFactory;
use ResponsiveMenu\Collections\OptionsCollection;

class OptionService {

	public function __construct(OptionRepository $repository, OptionFactory $factory) {
		$this->repository = $repository;
		$this->factory = $factory;
	}

	public function updateOptions(array $options) {

  	foreach($options as $key => $val)
  		$this->repository->update($this->factory->build($key, $val));

    $options = $this->all();

    $this->updateWpml($options);
    $this->buildFiles($options);

		return $options;
	}

	public function createOptions(array $options) {

  	foreach($options as $key => $val)
  		$this->repository->create($this->factory->build($key, $val));

    $options = $this->all();

  	$this->updateWpml($options);
  	$this->buildFiles($options);

  	return $options;
	}

	public function all() {
		return $this->repository->all();
	}

	public function updateWpml(OptionsCollection $options) {
    $wpml = new WPML;
		$wpml->saveFromOptions($options);
	}

	public function buildFiles(OptionsCollection $options) {
		if($options['external_files'] == 'on'):
    		$save_factory = new AdminSaveFactory();
    		$save_factory->build($options);
  	endif;
	}

  public function buildFromPostArray(array $post) {
    return $this->repository->buildFromArray($post);
  }

  public function combineOptions($default_options, $new_options) {
    return array_merge($default_options, array_filter($new_options, function($value) {
      return ($value !== null && $value !== false && $value !== '');
    }));
  }

}
