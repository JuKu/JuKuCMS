# Styles

Styles are the templates, including CSS and JS.\
Templates are written in **XTPL Template Engine** ~~or with **[Twig 2.0](https://twig.symfony.com/doc/2.x/templates.html)**~~.\
\
**NOTICE**:\
Twig is not longer supported, because of its bad performance - compared to XTemplate.\
You can find a benchmark in directory [/twig_performance/](../twig_performance).\
We have used a simple template file with 14 variables.\
\
**Benchmark Results**:

|         | Twig           | XTemplate  |
| --------- |:-------------:| :-----:|
| Without cache      | 32ms | 0.0000011920928955078ms (1.1920928955078E-6 seconds)  |
| With cache      | 10ms      |   0.00095367431640625 (9.5367431640625E-7 seconds) |

**Conclusion**:\
**XTemplate is 10.485x times faster than Twig**!

## style.json

Every style needs a file **style.json** in style root directory.\
This file contains all required information about the style.

```json
{
    "css": [
        "bower_components/bootstrap/dist/css/bootstrap.min.css",
        "bower_components/font-awesome/css/font-awesome.min.css",
        "bower_components/Ionicons/css/ionicons.min.css",
        "dist/css/AdminLTE.min.css",
        "dist/css/skins/_all-skins.min.css"
    ]
}
```

## Style Rules

### CSS Files

Every good design needs css files. But its **not allowed** to access this css files directly like this:
```html
<link rel="stylesheet" href="styles/mystyle/css/css1.css" />
<link rel="stylesheet" href="styles/mystyle/css/css2.css" />
<link rel="stylesheet" href="styles/mystyle/css/css3.css" />
```

For performance reasons you have to include this HTML Code in header instead (replace "yourstylename" with your directory name):
```html
<link rel="stylesheet" href="css.php?style=yourstylename" />
```

Then you have to add all your required css files to **style.json** file in your style directory:
```json
{
    ...
    
    "css": [
        "css/css1.css",
        "css/css2.css",
        "css/css-file3.css"
    ]
    
    ...
}
```

If your template is shown by client, client requests **css.php** file which merges all this css files and adds a **E-Tag** and **Last-Modified-Header**, so browser doesnt have to download this files every request, only if they have changed.\
This increase your page speed drastically. Also css.php compress your css files, so bandwidth can so reduced and loading will be faster.

### Namingspace

Style-Names should only contains alphanumeric chars, this means a-z, A-Z and 0-9.\
All other characters are not allowed.