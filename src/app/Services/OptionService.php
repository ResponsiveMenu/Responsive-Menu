<?php

namespace ResponsiveMenu\Services;
use ResponsiveMenu\Repositories\OptionRepository;
use ResponsiveMenu\Translation\Translator;
use ResponsiveMenu\Factories\OptionFactory;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Filesystem\ScriptsBuilder;

class OptionService {

	public function __construct(OptionRepository $repository, OptionFactory $factory, Translator $translator, ScriptsBuilder $builder) {
		$this->repository = $repository;
		$this->factory = $factory;
		$this->translator = $translator;
		$this->builder = $builder;
	}

  public function all() {
		return $this->repository->all();
	}

	public function updateOptions(array $options) {

  	foreach($options as $key => $val)
  		$this->repository->update($this->factory->build($key, $val));

		return $this->processAfterSavingOptions();
	}

	public function createOptions(array $options) {

  	foreach($options as $key => $val)
  		$this->repository->create($this->factory->build($key, $val));

    return $this->processAfterSavingOptions();
	}

  private function processAfterSavingOptions() {
    $options = $this->all();
    $this->translator->saveTranslations($options);
    if($options['external_files'] == 'on')
      $this->builder->build($options);
    return $options;
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
