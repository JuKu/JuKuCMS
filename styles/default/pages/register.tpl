{if !$registration_enabled}
    <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; text-shadow: 0 0 2px orange; /* horizontal-offset vertical-offset 'blur' colour */ -moz-text-shadow: 0 0 2px orange; -webkit-text-shadow: 0 0 2px orange; ">Registration is disabled!</p>
{else}
    <form action="{$action_url}" method="post">
        <table border="0">
            <tr>
                <td>Username*: </td>
                <td>
                    <input type="text" name="username" placeholder="Username" required="required" />

                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN}" required="required" />
                </td>
            </tr>
            <tr>
                <td>E-Mail*: </td>
                <td><input type="email" name="mail" placeholder="john@example.com" required="required" /></td>
            </tr>
            <tr>
                <td>Password*: </td>
                <td><input type="password" name="password" placeholder="Password" required="required" /></td>
            </tr>
            <tr>
                <td>Reply Password*: </td>
                <td><input type="password" name="password_reply" placeholder="Password" required="required" /></td>
            </tr>

            <!-- custom profile fields -->

            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="Register" /></td>
            </tr>
        </table>
    </form>

    <hr />
    <p><b>*This field is required.</b></p>
{/if}