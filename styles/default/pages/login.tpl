<!-- BEGIN: main -->

<!-- BEGIN: no_username -->
<p style="color: red; ">No <b>Username</b> was set!</p><br />
<!-- END: no_username -->

<!-- BEGIN: no_password -->
<p style="color: red; ">No <b>Password</b> was set!</p><br />
<!-- END: no_password -->

<!-- BEGIN: error_msg -->
<p style="color: red; ">Error: {ERROR_MSG}</p><br />
<!-- END: error_msg -->

<!-- BEGIN: login_successful -->
<p style="color:green; ">Login successful! <a href="{REDIRECT_URL}">Redirect now</a>.</p>
<!-- END: login_successful -->

<!-- BEGIN: form -->
    {FILE "styles/default/pages/login_form.tpl"}
<!-- END: form -->

<!-- END: main -->