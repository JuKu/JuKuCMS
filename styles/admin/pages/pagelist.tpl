<div class="row">
    <div class="col-xs-12">
        {if $no_edit_permissions == true}
            <div class="alert alert-warning" role="alert">
                <strong>Warning!</strong> You are able to see all pages, but you don't have permissions to edit any of this pages!
            </div>
        {/if}

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{lang}All pages{/lang}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="pagetable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                {foreach $columns column}
                                    <th>{$column}</th>
                                {/foreach}
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $pagelist page}
                                <tr>
                                    <td>{$page.id}</td>
                                    <td><a href="{$page.url}" target="_blank"><span style="color: #0d6aad;">/{$page.alias}</span></a></td>
                                    <td><b style="color: {if $page.own_page == true}#00a7d0{else}#3F4C6B{/if}; ">{$page.title}</b></td>
                                    <td><span style="color: {if $page.user_online == true}#006E2E{else}#CC0000{/if}; " title="{if $page.user_online == true}User is online{else}User is offline{/if}">{$page.author}</span></td>
                                    <td><!-- {$page.state} -->
                                        <!-- <button type="button" class="btn btn-default btn-sm">
                                            <span class="glyphicon glyphicon-star"></span> Star
                                        </button> -->
                                        <!-- <span class="tag label label-success">
                                          <span>Published</span>
                                          <a><i class="remove glyphicon glyphicons-check glyphicon-white"></i></a>
                                        </span> -->
                                        {if $page.published == true}
                                            <span class="label label-success" title="Page was published">Published <i class=" fa fa-check-circle"></i></span>
                                        {else}
                                            <span class="label label-danger" title="Page wasn't published yet">Draft <i class=" fa fa-pen-square"></i></span>
                                        {/if}
                                    </td>
                                    <td>
                                        {if $page.locked == true}
                                            {if $permission_can_unlock_all_pages == true}
                                                <a href="{$page.unlock_url}" class="btn btn-primary" role="button" title="Unlock page">Unlock <i class=" fa fa-unlock"></i></button></a>
                                            {else}
                                                <button type="button" class="btn  disabled" title="Page was locked from user {$page.locked_user} at {$page.locked_timestamp}">Locked  <i class=" fa fa-lock"></i></button><!-- fa-lock fa-wrench -->
                                            {/if}
                                        {else}
                                            <!-- show action buttons -->
                                            {$page.actions}

                                            <a href="{$page.delete_url}" class="btn btn-danger" role="button" title="Delete page">Delete <i class=" fa fa-trash"></i></button></a>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                        <tfoot>
                            <tr>
                                {foreach $columns column}
                                    <th>{$column}</th>
                                {/foreach}
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <!-- content -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->