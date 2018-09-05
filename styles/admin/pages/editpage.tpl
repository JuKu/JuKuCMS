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
        <div class="col-md-9">
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- <div class="box-header with-border">
                    <h3 class="box-title">{lang}Edit page{/lang}</h3>
                </div> -->
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body pad">
                    <div class="form-group">
                        <label for="inputTitle" class="col-sm-2 control-label">Title</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" value="{$page.title}" class="form-control" id="inputTitle" placeholder="Page title" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFolder" class="col-sm-2 control-label">Alias</label>

                        <div class="col-sm-10" style="padding-bottom: 20px;">
                            <input type="text" name="page_alias" value="{$page.alias}" class="form-control" id="inputAlias" placeholder="my-page-alias" required="required" disabled="disableds">
                        </div>
                    </div>

                    {$additional_code_header}

                    <div class="form-group">
                        <br />
                        <br />
                        <br />
                        <br />

                        <div class="box-body pad">
                            <textarea id="wysiwygEditor" name="html_code" style="width: 100%; min-height: 400px; " rows="10">{$page.content}</textarea>
                        </div>
                    </div>

                    {$additional_code_footer}
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
        <div class="col-md-3">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Publish</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <!-- <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
                                title="Remove">
                            <i class="fa fa-times"></i></button> -->
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    Publish state
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="Submit" value="Save" class="btn btn-info{if $page.is_published == true} pull-right{/if}">{lang}Save{/lang}</button>

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