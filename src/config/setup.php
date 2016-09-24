<?php

add_action('init', function() use($container) {
  $migration = $container['migration'];
  $migration->setup();
  $migration->synchronise();
});
