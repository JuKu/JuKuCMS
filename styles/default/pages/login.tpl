<!-- BEGIN: main -->

<!-- BEGIN: no_username -->
<p style="color: red; ">No <b>Username</b> was set!</p><br />
<!-- END: no_username -->

<!-- BEGIN: no_password -->
<p style="color: red; ">No <b>Password</b> was set!</p><br />
<!-- END: no_password -->

<!-- BEGIN: error_msg -->
<p style="color: red; "><b>Error</b>: {ERROR_TEXT}</p><br />
<!-- END: error_msg -->

<!-- BEGIN: login_successful -->
<p style="color:green; ">Login successful! <a href="{REDIRECT_URL}">Redirect now</a>.</p>
<!-- END: login_successful -->

<!-- BEGIN: form -->
    {FILE "styles/default/pages/login_form.tpl"}
<!-- END: form -->

<!-- BEGIN: already_logged_in -->
<p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">You are already logged in! (Username: {USERNAME})</p>
<!-- END: already_logged_in -->

<!-- END: main -->