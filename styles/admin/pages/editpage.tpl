<div class="row">
    <form action="{$action_url}" method="post" role="form">
        {foreach $errors message}
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-exclamation-triangle"></i> {lang}Error{/lang}!</h4>
                {$message}
            </div>
        {/foreach}

        <!-- left column -->
        <div class="col-md-8">
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- <div class="box-header with-border">
                    <h3 class="box-title">{lang}Edit page{/lang}</h3>
                </div> -->
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputTitle" class="col-sm-2 control-label">Title</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" value="{$page.title}" class="form-control" id="inputTitle" placeholder="Page title" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFolder" class="col-sm-2 control-label">Alias</label>

                        <div class="col-sm-10">
                            /<input type="text" name="page_alias" value="{$page.alias}" class="form-control" id="inputAlias" placeholder="my-page-alias" required="required" disabled="disableds">
                        </div>
                    </div>

                    {$additional_code}

                    Add form here
                </div>
                <!-- /.box-body -->
                <!-- <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">{lang}Edit Page{/lang}</button>
                </div> -->
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->

        <!-- right column -->
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Publish</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    Publish state
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="Submit" value="Save" class="btn btn-info pull-right">{lang}Save{/lang}</button>
                    {if $page.is_published == false}<button type="submit" name="Submit" value="Publish" class="btn btn-primary pull-right">{lang}Publish{/lang}</button>{/if}
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->

    </form>
</div>
<!-- /.row -->