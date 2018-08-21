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
    <meta http-equiv="content-type" content="text/html; charset=windows-1252" />

    <link rel="stylesheet" href="{$BASE_URL}/css.php?style=texturedblue&amp;hash={$CSS_HASH_ALL}" />

    <!-- header javascript -->
    <script language="javascript" type="text/javascript" src="{$BASE_URL}/js.php?style=texturedblue&amp;hash={$JS_HASH_ALL_HEADER}&amp;position=header"></script>

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
                <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
                <!-- <li class="selected"><a href="index.html">Home</a></li>
                <li><a href="examples.html">Examples</a></li>
                <li><a href="page.html">A Page</a></li>
                <li><a href="another_page.html">Another Page</a></li>
                <li><a href="contact.html">Contact Us</a></li> -->
            </ul>
        </div>
    </div>
    <div id="site_content">
        <div class="sidebar">
            <!-- insert your sidebar items here -->
            <h3>Latest News</h3>
            <h4>New Website Launched</h4>
            <h5>August 1st, 2013</h5>
            <p>2013 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
            <p></p>
            <h4>New Website Launched</h4>
            <h5>August 1st, 2013</h5>
            <p>2013 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
            <h3>Useful Links</h3>
            <ul>
                <li><a href="#">link 1</a></li>
                <li><a href="#">link 2</a></li>
                <li><a href="#">link 3</a></li>
                <li><a href="#">link 4</a></li>
            </ul>
            <h3>Search</h3>
            <form method="post" action="#" id="search_form">
                <p>
                    <input class="search" type="text" name="search_field" value="Enter keywords....." />
                    <input name="search" type="image" style="border: 0; margin: 0 0 -9px 5px;" src="{$STYLE_PATH}style/search.png" alt="Search" title="Search" />
                </p>
            </form>
        </div>
        <div id="content">
            {$CONTENT}
        </div>
    </div>
    <div id="footer">
        Copyright &copy; textured_blue | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a> | Username: {$USERNAME}
    </div>
</div>

<!-- footer javascript -->
<script language="javascript" type="text/javascript" src="{$BASE_URL}/js.php?style=texturedblue&amp;hash={$JS_HASH_ALL_FOOTER}&amp;position=footer"></script>

{$FOOTER_SCRIPTS}

</body>
</html>
