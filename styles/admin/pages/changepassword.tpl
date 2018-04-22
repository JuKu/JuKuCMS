<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Change password{/lang}</strong></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{$form_action}" method="post" role="form">
                <div class="box-body">
                    <div class="form-group">
                        <label for="old_password">{lang}Old password{/lang}</label>
                        <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">{lang}New password{/lang}</label>
                        <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="retry_password">{lang}Retry password{/lang}</label>
                        <input type="password" name="retry_password" class="form-control" id="retry_password" placeholder="Password">
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{lang}Submit{/lang}</button>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->