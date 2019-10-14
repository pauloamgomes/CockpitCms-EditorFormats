App.$(document).on("init-wysiwyg-editor", function(e, editor) {
  if (editor.settings.modified !== undefined) {
    return;
  }

  if (editor.settings.format === undefined) {
    return;
  }

  App.callmodule("editorformats:getEditorFormat", [
    editor.settings.format
  ], "access").then(function(data) {
    if (data && data.result) {

      var options,
          lang = editor.settings.language || App.$data.user.i18n || document.documentElement.getAttribute('lang') || 'en';

      options = data.result;
      options.branding = false;
      options.selector = "#" + editor.id;
      options.modified = true;
      options.setup = editor.settings.setup;

      options.language = lang;
      options.language_url = lang == 'en' ? '' : App.route('/config/cockpit/i18n/tinymce/'+lang+'.js');

      options.relative_urls = false;
      if (options.absolute_urls) {
        options.remove_script_host = false;
      }

      tinymce.EditorManager.execCommand("mceRemoveEditor", false, editor.id);
      new tinymce.Editor(editor.id, options, tinymce.EditorManager).render();
    }
  });
});
