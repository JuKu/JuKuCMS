<div class="row">
    <div class="col-xs-12">
        {if $no_edit_permissions == true}
            <div class="alert alert-warning" role="alert">
                <strong>Warning!</strong> You are able to see all pages, but you don't have permissions to edit any of this pages!
            </div>
        {/if}

        {foreach $success_messages message}
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> {lang}Success{/lang}!</h4>
                {$message}
            </div>
        {/foreach}

        {if $show_trash == true}
            <div class="alert alert-info" role="alert">
                <strong>Information!</strong> You currently see only pages in trash. <a href="{$page_url}">Back to normal pages</a>
            </div>
        {/if}

        {if $show_trash == false}
            <a href="{$trash_url}" class="btn btn-app">
                <span class="badge bg-teal">{$pages_in_trash}</span>
                <i class="fa fa-trash"></i> Trash
            </a>
        {/if}

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{if $show_trash == false}{lang}All pages{/lang}{else}{lang}Trash{/lang}{/if}</h3>
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
                                        {if $page.is_in_trash == true}
                                            {if $permission_can_restore_trash_pages == true}<a href="{$page.restore_url}" class="btn btn-primary" role="button" title="Restore page">{lang}Restore{/lang} <i class=" fa fa-plus-square"></i></button></a>{/if}
                                            {if $permission_can_delete_all_pages_permanently == true}<a href="{$page.delete_permanently_url}" class="btn btn-danger" role="button" title="Delete page permanently, so you cannot restore them">{lang}Delete permanently{/lang} <i class=" fa fa-minus-circle"></i></button></a>{/if}
                                        {else}
                                            {if $page.locked == true}
                                                {if $permission_can_unlock_all_pages == true}
                                                    <a href="{$page.unlock_url}" class="btn btn-primary" role="button" title="Unlock page">{lang}Unlock{/lang} <i class=" fa fa-unlock"></i></button></a>
                                                {else}
                                                    <button type="button" class="btn  disabled" title="Page was locked from user {$page.locked_user} at {$page.locked_timestamp}">{lang}Locked{/lang}  <i class=" fa fa-lock"></i></button><!-- fa-lock fa-wrench -->
                                                {/if}
                                                {if $page.can_edit == true && $page.locked_by_me == true}<a href="{$page.edit_url}" class="btn btn-warning" role="button" title="Edit page">{lang}Edit{/lang} <i class=" fa fa-edit"></i></button></a>{/if}
                                            {else}
                                                <!-- action buttons -->
                                                {if $page.can_edit == true}<a href="{$page.edit_url}" class="btn btn-warning" role="button" title="Edit page">{lang}Edit{/lang} <i class=" fa fa-edit"></i></button></a>{/if}
                                                {if $page.can_delete == true}<a href="{$page.delete_url}" class="btn btn-danger" role="button" title="Delete page">{lang}Trash{/lang} <i class=" fa fa-trash"></i></button></a>{/if}
                                            {/if}
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
</div>
<!-- /.row -->