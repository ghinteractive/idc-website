<?php

namespace GHInt\Helpers;

use Generator;

/**
 * Generates lists of terms grouped by taxonomy
 *
 * @param array $taxonomies
 * @param array $opts
 * @return Generator
 */
function generateTermGroups(array $taxonomies, array $opts = [])
{
  foreach ($taxonomies as $tax) {
    $termOpts = array_merge($opts ?: array('hide_empty' => true), ['taxonomy' => $tax]);
    if ($terms = get_terms($termOpts)) {
      yield $tax => $terms;
    }
  }
}

/**
 * Generates lists of post terms grouped by taxonomy
 *
 * @param array $taxonomies
 * @param int $id
 * @param string|null $field
 * @return Generator
 */
function generatePostTermGroups(array $taxonomies, int $id, string $field = null)
{
  foreach ($taxonomies as $tax) {
    $terms = wp_get_post_terms($id, $tax);
    if ($terms) {
      yield $tax => $field ? wp_list_pluck($terms, $field) : $terms;
    }
  }
}

/**
 * Generates an array of class names based on true/false values
 *
 * @param array $classes
 * @return Generator
 */
function generateClasses(array $classes): Generator
{
  foreach ($classes as $class => $predicate) {
    if ($predicate === true) {
      yield $class;
    }
  }
}