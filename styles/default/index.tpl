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

        <a href="{BASE_URL}/{LOGIN_PAGE}">Login Page</a>

        <!-- BEGIN: logged-in -->
            <a href="{LOGOUT_URL}">Logout</a>
        <!-- END: logged-in -->

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
                <tr>
                    <td>LOGIN_URL</td>
                    <td>{LOGIN_URL}</td>
                </tr>
                <tr>
                    <td>LOGOUT_PAGE</td>
                    <td>{LOGOUT_PAGE}</td>
                </tr>
                <tr>
                    <td>LOGOUT_URL</td>
                    <td>{LOGOUT_URL}</td>
                </tr>
                <tr>
                    <td>Username (UserID)</td>
                    <td>{USERNAME} ({USERID})</td>
                </tr>
            </tbody>
        </table>

        {FOOTER}
    </body>
</html>
<!-- END: main -->