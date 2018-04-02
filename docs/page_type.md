# Page Types

RocketCMS contains more page types to be more flexible.\
Users can choose a page type on page creation, for example "HTMLPage".\
But there can also be page types like "LogoutPage".\
\
Basic (pre-installed) page types:

  - HTML Page
  - must be added later

## For Developers

### General

**Every PageType is a PHP class**!

### Register new page type

**Method**: PageType::createPageType (string $class_name, string $title, bool $advanced = false)\
\
Example:
```php
//create a new PageType
PageType::createPageType ("Plugin_MyPlugin_MyPageType", "title of page type");
```

### Remove page type

**Method**: PageType::removePageType (string $class_name)\
\
Example:
```php
//remove page type
PageType::removePageType("Plugin_MyPlugin_MyPageType");
```