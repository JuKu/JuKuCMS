# Plugins

RocketCMS also supports **plugins**.\
You can find all plugins in directory "**/plugins/**".

## Plugin Types

Like [composer](https://getcomposer.org/doc/04-schema.md#type) this CMS supports multiple plugin types:

  - **library** (plugins which only provide an api for other plugins, but dont add features to CMS itself)
  - **metaplugin** (An empty plugin that contains requirements and will trigger their installation, but contains no files and will not write anything to the filesystem)
  - **project** (a full plugin which adds new features to CMS)