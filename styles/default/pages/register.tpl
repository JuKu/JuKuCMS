{if !$registration_enabled}
    <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; text-shadow: 0 0 2px orange; /* horizontal-offset vertical-offset 'blur' colour */ -moz-text-shadow: 0 0 2px orange; -webkit-text-shadow: 0 0 2px orange; ">Registration is disabled!</p>
{else}
    <form action="{$action_url}" method="post">
        <table border="0">
            {foreach $fields field}
                <tr>
                    <td>{$field.title}{if $field.required}*{/if}: </td>
                    <td>
                        {if !$field.custom_html}
                            <input type="{$field.type}" name="{$field.name}" placeholder="{$field.placeholder}"{if $field.required} required="required"{/if} value="{$field.value}" />
                        {else}
                            {$field.html}
                        {/if}
                    </td>
                </tr>
            {/foreach}

            <!-- custom profile fields -->

            <tr>
                <td>&nbsp;</td>
                <td>
                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN}" required="required" />

                    <input type="submit" name="submit" value="Register" />
                </td>
            </tr>
        </table>
    </form>

    <hr />
    <p><b>*This field is required.</b></p>
{/if}