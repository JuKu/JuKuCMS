{if $no_token == true}
    <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; text-shadow: 0 0 2px orange; /* horizontal-offset vertical-offset 'blur' colour */ -moz-text-shadow: 0 0 2px orange; -webkit-text-shadow: 0 0 2px orange; ">
        Invalide URL! No token was set!
    </p>
{else}
    {if $invalide_token}
        <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; text-shadow: 0 0 2px orange; /* horizontal-offset vertical-offset 'blur' colour */ -moz-text-shadow: 0 0 2px orange; -webkit-text-shadow: 0 0 2px orange; ">
            Token is invalide!
        </p>
    {else}
        <p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">Your account was activated successfully! You can login now!</p>
    {/if}
{/if}