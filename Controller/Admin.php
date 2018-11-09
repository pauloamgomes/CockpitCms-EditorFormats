<?php

namespace EditorFormats\Controller;

use \Cockpit\AuthController;

/**
 * Admin controller class.
 */
class Admin extends AuthController {

  /**
   * Default index controller.
   */
  public function index() {
    if (!$this->app->module('cockpit')->hasaccess('editorformats', 'manage')) {
      return FALSE;
    }

    $formats = $this->module("editorformats")->formats(TRUE);

    return $this->render('editorformats:views/formats/index.php', [
      'formats' => $formats,
    ]);
  }

  /**
   * Format controller.
   */
  public function format($name = NULL) {
    if (!$this->app->module('cockpit')->hasaccess('editorformats', 'manage')) {
      return FALSE;
    }

    $defaultFormat = $this->module('editorformats')->defaultFormat();
    $format = [];

    if ($name) {
      if (!$format = $this->module('editorformats')->format($name)) {
        return FALSE;
      }
    }

    $format = array_replace_recursive($defaultFormat, $format);

    return $this->render('editorformats:views/formats/format.php', compact('format'));
  }

}
