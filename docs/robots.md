# robots.txt

For search engines this CMS automatically generates a [robots.txt](https://support.google.com/webmasters/answer/6062596?hl=de) file.\
This rules are loaded from database table **rules**.

## Api

You can add or remove robots.txt rules with class **Robots**.

  - public static function addRule (string $option, string $value, string $useragent = "*")
  - public static function removeRule (string $option, string $value, string $useragent = "*")
  - public static function listRules ()

**Allowed options**:

  - ALLOW
  - DISALLOW
  - SITEMAP
  - CRAWL-DELAY
  
**value**: Directory / value\
**useragent**: Here you can specify useragent, so you can disallow or allow crawling of a directory only for a specific crawler

## Example Usage

```php
//dont allow all robots to crawl directory /system/ with all sub-directories
Robots::addRule("DISALLOW", "/system/*");

//disallow crawling of directory /dir1/ only for googlebot
Robots::addRule("DISALLOW", "/dir1/*", "Googlebot");

//allow all robots to crawl directory /store/ with all sub-directories
Robots::addRule("ALLOW", "/store/*");

//disallow /dir1/ but allow /dir1/dir2/
Robots::addRule("DISALLOW", "/dir1/");
Robots::addRule("ALLOW", "/dir1/dir2/");

//set sitemap
Robots::addRule("SITEMAP", "http://www.example.com/sitemap.xml");
```