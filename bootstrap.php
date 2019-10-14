<?php

/**
 * @file
 * Cockpit module bootstrap implementation.
 */


/**
 * Editor formats module functions.
 */
$this->module('editorformats')->extend([

  'defaultFormat' => function() {
    return [
      'resize' => TRUE,
      'absolute_urls' => FALSE,
      'branding' => FALSE,
      'menubar' => [
        'edit' => TRUE,
        'insert' => TRUE,
        'view' => TRUE,
        'format' => TRUE,
        'table' => TRUE,
        'tools' => TRUE,
      ],
      'toolbar' => 'searchreplace | bold italic subscript superscript strikethrough underline | link unlink | alignleft aligncenter alignright | numlist bullist | removeformat | code fullscreen',
      'blocks' => [
        'p' => TRUE,
        'h1' => TRUE,
        'h2' => TRUE,
        'h3' => TRUE,
        'h4' => TRUE,
        'h5' => TRUE,
        'h6' => TRUE,
        'pre' => TRUE,
      ],
      'plugins' => [
        'link' => TRUE,
        'anchor' => FALSE,
        'image' => FALSE,
        'lists' => TRUE,
        'preview' => TRUE,
        'hr' => TRUE,
        'anchor' => FALSE,
        'code' => TRUE,
        'fullscreen' => TRUE,
        'media' => FALSE,
        'cpmediapath' => FALSE,
        'cpassetpath' => FALSE,
        'table' => TRUE,
        'contextmenu' => FALSE,
        'paste' => FALSE,
        'wordcount' => FALSE,
        'visualblocks' => FALSE,
        'visualchars' => FALSE,
        'tabfocus' => FALSE,
        'noneditable' => FALSE,
        'insertdatetime' => FALSE,
        'codesample' => FALSE,
        'advlist' => FALSE,
        'textcolor' => FALSE,
        'pagebreak' => FALSE,
        'imagetools' => FALSE,
        'emoticons' => FALSE,
        'colorpicker' => FALSE,
        'charmap' => FALSE,
        'autoresize' => FALSE,
        'directionality' => FALSE,
        'cpcollectionlink' => FALSE,
        'toc' => FALSE,
        'searchreplace' => FALSE,
      ],
      'height' => '400',
    ];
  },

  'createFormat' => function ($name, $data = []) {
    if (!trim($name)) {
      return FALSE;
    }

    $configpath = $this->app->path('#storage:') . '/editorformats';

    if (!$this->app->path('#storage:editorformats')) {
      if (!$this->app->helper('fs')->mkdir($configpath)) {
        return FALSE;
      }
    }

    if ($this->exists($name)) {
        return FALSE;
    }

    $time = time();

    $format = $this->defaultFormat();
    $format['_id'] = uniqid($name);
    $format['name'] = $name;
    $format['_created'] = $time;
    $format['_modified'] = $time;

    $format = array_replace_recursive($format, $data);

    $export = var_export($format, TRUE);

    if (!$this->app->helper('fs')->write("#storage:editorformats/{$name}.format.php", "<?php\n return {$export};")) {
        return FALSE;
    }

    return $format;
  },

  'updateFormat' => function ($name, $data) {
    $metapath = $this->app->path("#storage:editorformats/{$name}.format.php");

    if (!$metapath) {
      return FALSE;
    }

    $data['_modified'] = time();

    $format  = include $metapath;
    $format  = array_merge($format, $data);

    $export  = var_export($format, TRUE);

    if (!$this->app->helper('fs')->write($metapath, "<?php\n return {$export};")) {
      return FALSE;
    }

    return $format;
  },

  'saveFormat' => function ($name, $data) {
    if (!trim($name)) {
      return FALSE;
    }

    return isset($data['_id']) ? $this->updateFormat($name, $data) : $this->createFormat($name, $data);
  },

  'removeFormat' => function ($name) {

    if ($format = $this->format($name)) {
      $this->app->helper("fs")->delete("#storage:editorformats/{$name}.format.php");
      return TRUE;
    }

    return FALSE;
  },

  'exists' => function ($name) {
      return $this->app->path("#storage:editorformats/{$name}.format.php");
  },

  'formats' => function ($extended = FALSE) {

    $stores = [];

    foreach ($this->app->helper("fs")->ls('*.format.php', '#storage:editorformats') as $path) {

      $store = include $path->getPathName();

      if ($extended) {
        $store['itemsCount'] = $this->count($store['name']);
      }

      $stores[$store['name']] = $store;
    }

    return $stores;
  },

  'format' => function ($name) {
    static $formats;

    if (is_null($formats)) {
      $formats = [];
    }

    if (!is_string($name)) {
      return FALSE;
    }

    if (!isset($formats[$name])) {

      $formats[$name] = FALSE;

      if ($path = $this->exists($name)) {
        $formats[$name] = include $path;
      }
    }

    return $formats[$name];
  },

  'getEditorFormat' => function ($name) {
    $format = $this->format($name);
    $options = [];
    $options['branding'] = (bool) $format['branding'];
    $options['resize'] = (bool) $format['resize'];
    $options['absolute_urls'] = (bool) $format['absolute_urls'];
    $options['height'] = (int) $format['height'];
    $options['menubar'] = trim(implode(' ', array_keys(array_filter($format['menubar']))));
    $options['plugins'] = array_keys(array_filter($format['plugins']));
    $options['toolbar'] = $format['toolbar'];
    $blocks = [
      'p' => 'Paragraph=p',
      'h1' => 'Header 1=h1',
      'h2' => 'Header 2=h2',
      'h3' => 'Header 3=h3',
      'h4' => 'Header 4=h4',
      'h5' => 'Header 5=h5',
      'h6' => 'Header 6=h6',
      'pre' => 'Pre=pre',
    ];
    if (!empty($format['blocks']) && is_array($format['blocks'])) {
      foreach ($format['blocks'] as $format => $status) {
        if (!$status) {
          unset($blocks[$format]);
        }
      }
    }
    $options['block_formats'] = implode("; ", $blocks);

    return $options;
  },

]);

// If admin include relevant files.
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
  include_once __DIR__ . '/admin.php';
}
