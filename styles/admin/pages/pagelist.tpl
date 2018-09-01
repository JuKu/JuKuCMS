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
                                    <td><a href="{$page.url}" target="_blank"><span style="color: #0d6aad;">{$page.alias}</span></a></td>
                                    <td><b style="color: {if $page.own_page == true}#00a7d0{else}#3F4C6B{/if}; ">{$page.title}</b></td>
                                    <td><span style="color: {if $page.user_online == true}#006E2E{else}#00a7d0{/if}; " title="{if $page.user_online == true}User is online{else}User is offline{/if}">{$page.author}</span></td>
                                    <td>{$page.state}</td>
                                    <td>{$page.actions}</td>

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