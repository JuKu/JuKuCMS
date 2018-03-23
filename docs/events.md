# Events

## Throw event

Example:
```php
Events::throwEvent("event-name", array(
	'file' => &$file,
	'template' => &$this,
	'template_instance' => &$this->template,
	'registry' => &$registry
));
```

  - First argument: **name of event**, should be unique
  - second argument: **parameters** (array with all parameters you want to add, so events can read or with `&` also modify values
  
## Event Namespace

  - **Core**: `eventname`
  - **Plugins**: `plugin_<plugin name>_<eventname>`
  - **Styles**: `style_<style-name>_<eventname>`