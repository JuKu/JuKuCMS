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

        <p>Your username: {USERNAME} (UserID: {USERID})</p><br />
        <hr />
        <br />
        <h2>Login</h2>

        <form action="{CURRENT_PAGE_URL}" method="post">
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

        <hr />

        <table border="1">
            <thead>
                <tr>
                    <th><b>Template Variable</b></th>
                    <th><b>Value</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>DOMAIN</td>
                    <td>{DOMAIN}</td>
                </tr>
                <tr>
                    <td>BASE_URL</td>
                    <td>{BASE_URL}</td>
                </tr>
                <tr>
                    <td>CURRENT_URL</td>
                    <td>{CURRENT_URL}</td>
                </tr>
                <tr>
                    <td>FOLDER</td>
                    <td>{FOLDER}</td>
                </tr>
                <tr>
                    <td>PREF_LANG</td>
                    <td>{PREF_LANG}</td>
                </tr>
                <tr>
                    <td>LANG_TOKEN</td>
                    <td>{LANG_TOKEN}</td>
                </tr>
                <tr>
                    <td>HOME_PAGE</td>
                    <td>{HOME_PAGE}</td>
                </tr>
                <tr>
                    <td>LOGIN_PAGE</td>
                    <td>{LOGIN_PAGE}</td>
                </tr>
            </tbody>
        </table>

        {FOOTER}
    </body>
</html>
<!-- END: main -->