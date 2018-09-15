<div class="row">
    <form action="{$action_url}" method="post" role="form">
        <div class="col-xs-12">
            {foreach $errors message}
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-exclamation-triangle"></i> {lang}Error{/lang}!</h4>
                    {$message}
                </div>
            {/foreach}
        </div>
        <!-- /.col -->

        <div class="col-xs-12">
            {foreach $success_messages message}
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> {lang}Success{/lang}!</h4>
                    {$message}
                </div>
            {/foreach}
        </div>
        <!-- /.col -->

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
                    <a href="{$page.preview_url}" target="_blank" class="btn btn-default" role="button" title="Preview page">{lang}Preview{/lang} <i class=" fa fa-search"></i></button></a><br />
                    <br />
                    Publish state<br />
                    PageID: {$page.id}
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="submit" value="Save" class="btn btn-info">{lang}Save{/lang}</button>
                    <button type="submit" name="submit" value="SaveUnlock" class="btn btn-warning{if $page.is_published == true} pull-right{/if}">{lang}Save & Unlock{/lang}</button>

                    {if $page.is_published == false}<button type="submit" name="submit" value="Publish" class="btn btn-primary pull-right"{if $page.can_publish == false} disabled="disabled" title="You don't have permissions to publish this page"{else}title="Publish page"{/if}>{lang}Publish{/lang}</button>{/if}
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->

            <!-- Box: SEO -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{lang}Page Attributes{/lang}</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputFolder" class="col-sm-2 control-label">Folder</label>

                        <div class="col-sm-10">
                            <input type="text" name="folder" class="form-control" id="inputFolder" placeholder="/" value="{$page.folder}" title="Folder isn't changeable after page creation" disabled="disabled" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputParent" class="col-sm-2 control-label">Parent</label>

                        <div class="col-sm-10">
                            <select name="parent" class="form-control" id="inputParent">
                                <option value="-1"{if $page.parent == -1} selected="selected"{/if}>Mainpage (default)</option>
                                {foreach $parent_pages page1}
                                    <option value="{$page1.id}"{if $page.parent == $page1.id} selected="selected"{/if}>{$page1.alias}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputStyle" class="col-sm-2 control-label">Design</label>

                        <div class="col-sm-10">
                            <select name="design" class="form-control" id="inputStyle">
                                <option value="none"{if $page.current_style == "none"} selected="selected"{/if}>Default Style</option>
                                {foreach $styles style}
                                    <option value="{$style}"{if $style == $page.current_style} selected="selected"{/if}>{$style}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTpl" class="col-sm-2 control-label">Custom Template</label>

                        <div class="col-sm-1">
                            <input type="checkbox" name="has_custom_template" id="customTplCheckbox"{if $page.has_custom_template == true} checked="checked"{/if} />
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="template" class="form-control" id="inputTpl" placeholder="none" value="{$page.template}"{if $page.has_custom_template == false} disabled="disabled"{/if} />
                        </div>
                    </div>
                    <!-- Parent page, template, menus -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Box: SEO -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{lang}SEO & Meta Data{/lang}</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputMetaDesc" class="col-sm-4 control-label" title="Meta description">Meta Desc.</label>

                        <div class="col-sm-8">
                            <input type="text" name="meta_description" class="form-control" id="inputMetaDesc" placeholder="Meta description of page" value="{$page.meta_description}" title="Meta Description" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputMetaKeywords" class="col-sm-4 control-label">Meta Keywords</label>

                        <div class="col-sm-8">
                            <input type="text" name="meta_keywords" class="form-control" id="inputMetaKeywords" placeholder="keyword1, keyword2" value="{$page.meta_description}" title="Meta Keywords - Attention! Search engines doesn't observe meta keywords anymore!" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputRobots" class="col-sm-4 control-label">Robots</label>

                        <div class="col-sm-8">
                            {* <select name="robots" class="form-control" id="inputRobots">
                                {foreach $robots_options option}
                                    <option value="{$option}"{if $page.meta_robots == $option} selected="selected"{/if}>{$option}</option>
                                {/foreach}
                            </select> *}
                            <input type="text" name="meta_robots" class="form-control" id="inputRobots" placeholder="index, follow" value="{$page.meta_robots}" title="Meta robots tag" />
                        </div>
                    </div>
                    <!-- SEO, Meta Data, Sitemap and Robots.txt settings -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Box: Permissions -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">{lang}Permissions{/lang}</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    Permissions
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->

    </form>
</div>
<!-- /.row -->