<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Facebook Feed Api{/lang}</strong></h3>
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

                    {if empty($success_message) === false}
                        <p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">{$success_message}</p>
                    {/if}

                    <p>To use this plugin you have to set a facebook pageID (https://www.facebook.com/&lt;Page-ID&gt;/).
                    </p>

                    <div class="form-group">
                        <label for="pageID">{lang}Facebook Page-ID{/lang}</label>
                        <input type="text" name="pageID" class="form-control" id="pageID" placeholder="Facebook Page-ID" required="required" value="{$pageID}">
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