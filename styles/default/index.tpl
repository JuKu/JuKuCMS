<!-- BEGIN: main -->
<!DOCTYPE html>
<html>
    <head>
        <title>{TITLE}</title>

        {HEAD}
    </head>
    <body>
        {HEADER}

        <h1>{RAW_TITLE}</h1><br />

        {CONTENT}

        <p>Your username: {USERNAME} (UserID: {USERID})</p>

        <form action="{CURRENT_PAGE_URL}" method="post">
            <table>
                <tr>
                    <td>Username: </td>
                    <td><input type="text" name="username" maxlength="255" required="required" placeholder="Username" /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" maxlength="255" required="required" placeholder="Password" />

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

        {FOOTER}
    </body>
</html>
<!-- END: main -->