{if !empty($error_message)}
    <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; ">
        {$error_message}
    </p>
{/if}

{if !empty($success_message)}
    <p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">{$success_message}</p>
{/if}