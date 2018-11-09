App.$(document).on("init-wysiwyg-editor", function(e, editor) {
  if (editor.settings.modified !== undefined) {
    return;
  }

  if (editor.settings.format === undefined) {
    return;
  }

  App.callmodule("editorformats:getEditorFormat", [
    editor.settings.format,
  ]).then(function(data) {
    if (data && data.result) {
      options = data.result;
      options.branding = false;
      options.selector = "#" + editor.id;
      options.modified = true;
      options.setup = editor.settings.setup;
      tinymce.EditorManager.execCommand("mceRemoveEditor", false, editor.id);
      new tinymce.Editor(editor.id, options, tinymce.EditorManager).render();
    }
  });
});
