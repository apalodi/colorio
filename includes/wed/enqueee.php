<?php

// application.css
\add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 10);
// application.js
\add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

// applicationAdmin.css
\add_action('admin_enqueue_scripts', [$this, 'enqueueStyles'], 50);
// applicationAdmin.js
\add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);

// Editor only script. applicationBlocksEditor.js
\add_action('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorScript']);

// Editor only style. applicationBlocksEditor.css
\add_action('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorStyle'], 50);

// Editor and frontend style. applicationBlocks.css
\add_action('enqueue_block_assets', [$this, 'enqueueBlockStyle'], 50);

// Frontend only script. applicationBlocks.js
\add_action('wp_enqueue_scripts', [$this, 'enqueueBlockScript']);
