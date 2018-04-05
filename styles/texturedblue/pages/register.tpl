{if !$registration_enabled}
    <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; text-shadow: 0 0 2px orange; /* horizontal-offset vertical-offset 'blur' colour */ -moz-text-shadow: 0 0 2px orange; -webkit-text-shadow: 0 0 2px orange; ">Registration is disabled!</p>
{else}
    <form action="{$action_url}" method="post">
        <div class="form_settings">
            {foreach $fields field}
                <p><span>{$field.title}{if $field.required}*{/if}: </span>
                    {if !$field.custom_html}
                        <input type="{$field.type}" name="{$field.name}" placeholder="{$field.placeholder}"{if $field.required} required="required"{/if} class="contact" value="{$field.value}" />{$field.text_behind}
                    {else}
                        {$field.html}
                    {/if}
                </p>
            {/foreach}

            <!-- custom profile fields -->

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN}" required="required" />

            <p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="name" value="Register" /></p>
        </div>
    </form>

    <hr />
    <p><b>*This field is required.</b></p>
{/if}