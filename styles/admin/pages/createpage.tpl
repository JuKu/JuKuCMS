<div class="row">
    <form action="{$action_url}" method="post" role="form">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{lang}Create page{/lang}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputFolder" class="col-sm-2 control-label">Alias</label>

                        <div class="col-sm-5">
                            <select name="folder" class="form-control" id="inputFolder" title="Page Folder">
                                <!-- <option>/</option>
                                <option>/admin/</option> -->
                                {foreach $folders folder}
                                    <option value="{$folder.folder}"{if $folder.is_root_folder == true} selected="selected"{/if}>{$folder.folder}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="inputAlias" placeholder="my-page-alias">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTitle" class="col-sm-2 control-label">Title</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputTitle" placeholder="My page title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPageType" class="col-sm-2 control-label">PageType</label>

                        <div class="col-sm-10">
                            <select name="pagetype" class="form-control" id="inputPageType">
                                {foreach $pagetypes pagetype}
                                    <option value="{$pagetype.class_name}">{$pagetype.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="inputAuthor" class="col-sm-2 control-label">Author</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputAuthor" placeholder="John Doe" value="{$username}" disabled="disabled">
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                    <button type="submit" class="btn btn-primary pull-right">{lang}Create Page{/lang}</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->

            <!-- menu selection -->
            {* <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{lang}Menus{/lang}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputGlobalMenu" class="col-sm-2 control-label">Global Menu</label>

                        <div class="col-sm-10">
                            <select name="global_menu" class="form-control" id="inputGlobalMenu">
                                <option value="-1" selected="selected">Default global menu</option>
                                {foreach $menus menu}
                                    <option value="{$menu.id}">{$menu.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLocalMenu" class="col-sm-2 control-label">Local Menu</label>

                        <div class="col-sm-10">
                            <select name="local_menu" class="form-control" id="inputLocalMenu">
                                <option value="-1" selected="selected">Default local menu</option>
                                {foreach $menus menu}
                                    <option value="{$menu.id}">{$menu.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->*}
        </div>
        <!--/.col (left) -->

        <!-- right column -->
        {* <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO (Search Engine Optimization)</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <!-- checkbox -->
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show_in_sitemap">
                                Show in sitemap
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSitemapChangeFreq" class="col-sm-2 control-label">Sitemap change frequency</label>

                        <div class="col-sm-10">
                            <select name="sitemap_changefreq" class="form-control" id="inputSitemapChangeFreq">
                                <option>ALWAYS</option>
                                <option>HOURLY</option>
                                <option>DAILY</option>
                                <option selected="selected">WEEKLY</option>
                                <option>MONTHLY</option>
                                <option>YEARLY</option>
                                <option>NEVER (avoid this!)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Horizontal Form -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Sidebars</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputLeftSidebar" class="col-sm-2 control-label">Left Sidebar</label>

                        <div class="col-sm-10">
                            <select name="left_sidebar" class="form-control" id="inputLeftSidebar">
                                <option value="-1" selected="selected">Default left sidebar</option>
                                {foreach $sidebars sidebar}
                                    <option value="{$sidebar.id}">{$sidebar.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputRightSidebar" class="col-sm-2 control-label">Right Sidebar</label>

                        <div class="col-sm-10">
                            <select name="right_sidebar" class="form-control" id="inputRightSidebar">
                                <option value="-1" selected="selected">Default right sidebar</option>
                                {foreach $sidebars sidebar}
                                    <option value="{$sidebar.id}">{$sidebar.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                    <button type="submit" class="btn btn-info btn-primary">{lang}Create Page{/lang}</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->*}
    </form>
</div>
<!-- /.row -->