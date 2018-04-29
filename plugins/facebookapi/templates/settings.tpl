<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Facebook Graph-Api{/lang}</strong></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{$form_action}" method="post" role="form">
                <div class="box-body">
                    {if empty($error_message) === false}
                        <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; ">
                            {$error_message}
                        </p>
                    {/if}

                    {if $form_submit === true}
                        <p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">{$success_message}</p>
                    {/if}

                    <p>To use this plugin you have to create a facebook app and insert appID and secret. Only with these credentials this plugin can read data from facebook api!<br />
                        <a href="https://developers.facebook.com/docs/apps/register?locale=de_DE" target="_blank">Register app</a> | <a href="https://developers.facebook.com/docs/apps/register?locale=de_DE#create-app" target="_blank">Create App ID</a>
                    </p>

                    <div class="form-group">
                        <label for="appID">{lang}Facebook App ID{/lang}</label>
                        <input type="text" name="appID" class="form-control" id="appID" placeholder="Facebook appID" required="required" value="{$appID}">
                    </div>
                    <div class="form-group">
                        <label for="new_password">{lang}Facebook Secret Key{/lang}</label>
                        <input type="password" name="secret_key" class="form-control" id="secret_key" placeholder="Facebook secret key" required="required" value="{$secret_key}">

                        <!-- CSRF token -->
                        <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN}" />
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary">{lang}Submit{/lang}</button>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->