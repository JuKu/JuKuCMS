# Widgets

Widgets can be used in sidebar(s) or admin dashboards.

## How to create custom Widget Types?

To add custom widgets you have to create a plugin and create a new class which has to **extends class Widget**.

Example:
```php
<?php

class TextWidget extends Widget {

	/**
	 * get html code which is shown on website
	 */
	public function getCode() {
		return "this html code is shown in sidebar.";
	}

	/**
	 * get formular html code which is shown in admin area if user edits the widget
	 */
	public function getAdminForm() {
		return "this html code is shown for edit widget in admin area.";
	}

	/**
	 * save widget data when new settings are saved in the admin area
	 */
	public function save() {
		//this function will be called for saving form from getAdminForm()
	}

}

?>
```

### Register new custom widget type

```php
//register new widget type, params: class name, title, description, editable, owner
WidgetType::register("TextWidget", "Text Widget", "Widget which shows text without html code.", true, "plugin_<MY_PLUGIN_NAME>");
```

### Unregister new custom widget type

```php
//unregister widget type by class name
WidgetType::unregister("TextWidget");

//unregister all widgets from this plugin
WidgetType::unregisterByOwner("plugin_<MY_PLUGIN_NAME>");
```