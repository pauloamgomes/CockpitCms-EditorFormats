# WYSIWYG Editor Formats

This addon extends Cockpit CMS core functionality by introducing the possibility to define editor format modes to the WYSIWYG field (based on tinymce editor).
When configuring a WYSIWYG field, cockpit already provides the possiblity to extend the default settings on the field definition:

```json
{
  "editor": {
    "height": 500,
    "menubar": "edit insert view format",
    "plugins": [
        "link lists preview hr anchor",
        "code fullscreen",
    ]
  }
}
```

 that is quite interesting, but can be painful and confusing when dealing with many fields, so that's where this addon can help, you just need to configure your formats (e.g. Basic, Advanced, etc..) and use the format name on your field instead of the configuration:

```json
{
  "editor": {
    "format": "Basic",
  }
}
```

## Installation

1. Confirm that you have Cockpit CMS (Next branch) installed and working.
2. Download zip and extract to 'your-cockpit-docroot/addons' (e.g. cockpitcms/addons/EditorFormats)
3. Access module settings (http://your-cockpit-site/editor-formats) and confirm that page loads.

## Configuration

The Addon doesn't require any extra configuration.
When enabled, it will be available to the admin with all features.

### Permissions

There are two permissions
 - manage - that can be used to manage the formats
 - access - is used to use the editor, if you are using the editor as non admin user you need to add that permission

example of configuration:

```yaml
groups:
  manager:
    editorformats:
      manage: true
      access: true
  editor:
    editorformats:
      access: true
```


## Usage

Create the formats you need, in most of the cases you only need a Basic format with minimum features:

![Basic Format](https://monosnap.com/image/A9K2yM0mPTF3ObbYgFv1BoYHWwaZ4E.png)

Edit your YYSIWYG fields and set the format:

```json
{
  "editor": {
    "format": "Basic",
  }
}
```

And when editing a collection that is using that field your editor should look like below:

![Basic format example](https://monosnap.com/image/xJ0UigiFr3FrilQX4CgWofuxTUidop.png)

However if you need more features from the editor create a new format (e.g. Advanced):

![Advanced Format](https://monosnap.com/image/Bv6GpRQhHjcJ5DyHamWM14LJIup4hg.png)

Edit your YYSIWYG fields and set the format:

```json
{
  "editor": {
    "format": "Advanced",
  }
}
```

And when editing a collection that is using that field your editor should look like below:

![Advanced format example](https://monosnap.com/image/x5DKC3ypF2Ys4mAY35gQfh6EomO0fd.png)

## Demo

[![Addon screencast](http://img.youtube.com/vi/ZKx8KztgCIE/0.jpg)](http://www.youtube.com/watch?v=ZKx8KztgCIE "WYSIWYG Editor Formats")

## Copyright and license

Copyright 2018 pauloamgomes under the MIT license.
