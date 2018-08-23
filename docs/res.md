# Resources

## CSS Files

This block in .tpl (template) files shows the correct css stylesheet
```smarty
<!-- includes css, listed in style.json -->
{res}css{/res}
```

This generates:
```html
<link rel="stylesheet" href="YOUR_URL/css.php?style=SELECTTED_STYLE&amp;media=ALL&amp;position=header&amp;hash=caf55fb1ed7159cedc7b9094014ad1ce" />
```

Please dont include css / js files in the "normal" way, because the CMS-way with this dwoo plugin is more efficient!

## JS Files

Same as css files:
```smarty
{res}js{/res}

<!-- or something like this -->
{res load="async"}js_footer{/res}
```