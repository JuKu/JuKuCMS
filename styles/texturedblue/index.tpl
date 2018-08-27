<!DOCTYPE HTML>
<html>

<head>
    <title>{$TITLE}</title>
    <link rel="shortcut icon" href="{$STYLE_PATH}style/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{$STYLE_PATH}style/favicon.ico" type="image/x-icon">	
    <link rel="shortcut icon" href="{$STYLE_PATH}style/favicon.png">
    <link rel="icon" href="{$STYLE_PATH}style/favicon.png">	
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset={$CHARSET}" />

    <!-- set charset -->
    <meta charset="{$CHARSET}" />

    {res media="ALL"}css{/res}
    {res load="async"}css_background{/res}

    {res load="async"}js{/res}
    {$HEAD}
</head>

<body>
<div id="main">
    <div id="header">
        <div id="logo">
            <div id="logo_text">
                <!-- class="logo_colour", allows you to change the colour of the text -->
                <h1><a href="/">Rocket<span class="logo_colour">CMS</span></a></h1>
                <h2>A simple demo website with a demo design</h2>
            </div>
        </div>
        <div id="menubar">
            <ul id="menu">
                {$MENU}
            </ul>
        </div>
    </div>
    <div id="site_content">
        <div class="sidebar">
            {foreach $sidebars.right_sidebar widget}
                <h3>{$widget.title}</h3>

                <!-- widget html code -->
                {if $widget.use_template == true}
                    <p>{$widget.code}</p>
                {else}
                    {$widget.code}
                {/if}
            {/foreach}
        </div>
        <div id="content">
            {$CONTENT}
        </div>
    </div>
    <div id="footer">
        Copyright &copy; textured_blue | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a> | Username: {$USERNAME}
    </div>
</div>

{res load="async"}js_footer{/res}

{$FOOTER_SCRIPTS}

</body>
</html>
