/**
 * Custom Gutenberg Blocks
 */

(function() {
  'use strict';

  // Register block styles
  wp.blocks.registerBlockStyle('core/button', {
    name: 'primary',
    label: 'Primary',
  });

  wp.blocks.registerBlockStyle('core/group', {
    name: 'hero',
    label: 'Hero',
  });

  wp.blocks.registerBlockStyle('core/group', {
    name: 'section',
    label: 'Section',
  });

  wp.blocks.registerBlockStyle('core/group', {
    name: 'section-alt',
    label: 'Section Alt',
  });

  wp.blocks.registerBlockStyle('core/columns', {
    name: 'cards',
    label: 'Cards',
  });

})();

