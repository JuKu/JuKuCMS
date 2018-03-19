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