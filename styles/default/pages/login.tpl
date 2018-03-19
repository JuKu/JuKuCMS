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
    <form action="{LOGIN_URL}" method="post">
        <table>
            <tr>
                <td>Username: </td>
                <td><input type="text" name="username" maxlength="255" required="required" placeholder="Username" /></td>
            </tr>
            <tr>
                <td>Password: </td>
                <td><input type="password" name="password" autocomplete="off" maxlength="255" required="required" placeholder="Password" />

                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="Login" /></td>
            </tr>
        </table>
    </form>
<!-- END: form -->

<!-- END: main -->