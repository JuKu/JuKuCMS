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

        <!-- BEGIN: not_logged_in -->
            <a href="{BASE_URL}/{LOGIN_PAGE}?redirect_url={CURRENT_URL}">Login Page</a>
        <!-- END: not_logged_in -->

        <!-- BEGIN: logged_in -->
            <a href="{BASE_URL}/admin/home">Admin Area</a> | <a href="{LOGOUT_URL}">Logout</a>
        <!-- END: logged_in -->

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
                    <td>USERNAME</td>
                    <td>{USERNAME}</td>
                </tr>
                <tr>
                    <td>IS_LOGGED_IN</td>
                    <td>{IS_LOGGED_IN}</td>
                </tr>
                <tr>
                    <td>USERID</td>
                    <td>{USERID}</td>
                </tr>
            </tbody>
        </table>

        {FOOTER}
    </body>
</html>
<!-- END: main -->