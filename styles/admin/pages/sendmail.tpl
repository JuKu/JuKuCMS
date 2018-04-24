<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Send HTML Mail{/lang}</strong></h3>
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

                    <div class="form-group">
                        <label for="to_mail">{lang}Receiver{/lang}</label>
                        <input type="email" name="to_mail" class="form-control" id="to_mail" placeholder="info@example.com" required="required">
                    </div>
                    <div class="form-group">
                        <label for="subject">{lang}Subject{/lang}</label>
                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" required="required">
                    </div>

                    <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" style="height: 300px">
                        Hello John Doe,

                        Here is my message.
                        Have you received it?

                        Best regards,

                        Jane Doe
                    </textarea>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> {lang}Send{/lang}</button>
                    </div>

                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> {lang}Discard{/lang}</button>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->