# Plugins

RocketCMS also supports **plugins**.\
You can find all plugins in directory "**/plugins/**".

## Plugin Types

Like [composer](https://getcomposer.org/doc/04-schema.md#type) this CMS supports multiple plugin types:

  - **library** (plugins which only provide an api for other plugins, but dont add features to CMS itself)
  - **metaplugin** (An empty plugin that contains requirements and will trigger their installation, but contains no files and will not write anything to the filesystem)
  - **project** (a full plugin which adds new features to CMS)
  
## Plugin Rules

  - Plugins are not allowed to change code of core, another plugin or a theme
  - every plugin name should be unique
  - every plugin needs a own directory
    * allowed characters for directory: a-z, A-Z, 0-9, -
    * underscore **_** is **not** allowed
  - every plugin needs a **plugin.json** in own plugin directory
  - trial ware is not allowed in our plugin directory
  - plugins should not track user (CMS installation) without their agreement
    * plugins should not call external servers without explicit and authorized agreement
    * for example a registration at a service or a checkbox in plugin settings
  - plugin version must increased on every new release
  - respect trademarks & copyrights
  
## plugin.json

```json
{
    "name": "example-plugin",
    "type": "library",
    "title": "Example Plugin",
    "description": "An example plugin to show plugin structure.",
    "version": "1.0.0",
    "keywords": [
        "example", "example-plugin", "plugin"
    ],
    "categories": [
        "example"
    ],
    "homepage": "http://jukusoft.com",
    "license": "Apache 2.0",
    "authors": [
        {
            "name": "Justin Kuenzel",
            "email": "info@jukusoft.com",
            "homepage": "http://jukusoft.com",
            "role": "Project Founder & Lead Developer"
        }
    ],
    "support": {
        "email": "info@jukusoft.com",
        "source": "https://github.com/JuKu/JuKuCMS",
        "issues": "https://github.com/JuKu/JuKuCMS/issues"
    },
    "require": {
        "php": ">=7.0.8"
    }
}
```