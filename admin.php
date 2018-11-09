<?php

/**
 * @file
 * Cockpit EditoFormats admin functions.
 */

// Module ACL definitions.
$this("acl")->addResource('editorformats', [
  'manage',
]);

/*
 * add menu entry if the user has access to group stuff
 */
$this->on('cockpit.view.settings.item', function () {
  if ($this->module('cockpit')->hasaccess('editorformats', 'manage')) {
     $this->renderView("editorformats:views/partials/settings.php");
  }
});

$app->on('admin.init', function () use ($app) {
  // Bind admin routes /editor-formats.
  $this->bindClass('EditorFormats\\Controller\\Admin', 'editor-formats');
  // Add js hook to deal with editor settings.
  $this->helper('admin')->addAssets('editorformats:assets/editor-formats.js');
});
