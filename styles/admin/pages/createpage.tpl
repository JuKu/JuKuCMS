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
                            <select name="folder" class="form-control" id="inputFolder">
                                <option>/</option>
                                <option>/admin/</option>
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
                    <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input type="file" id="exampleInputFile">

                        <p class="help-block">Example block-level help text here.</p>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Check me out
                        </label>
                    </div> -->
                </div>
                <!-- /.box-body -->

                <!-- <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{lang}Create page{/lang}</button>
                </div> -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->

        <!-- right column -->
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Horizontal Form</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Remember me
                                </label>
                            </div>
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
        <!--/.col (right) -->
    </form>
</div>
<!-- /.row -->