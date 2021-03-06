<div class="row">
    <div class="col-xs-12">
        {if $save_failed == true}
            <div class="alert alert-danger">
                <strong>Error!</strong> Couldn't save settings, field '{$invalide_field}' is invalide!
            </div>
        {/if}

        {if $save_success == true}
            <!-- <div class="alert alert-success">
                <strong>Success!</strong> Settings was saved successfully!
            </div> -->
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> {lang}Success{/lang}!</h4>
                Settings was saved successfully!
            </div>
        {/if}

        <!-- see also https://bootsnipp.com/snippets/featured/panels-with-nav-tabs -->
        <div class="panel with-nav-tabs panel-default">
            <form action="{$form_action}" method="post" role="form">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        {foreach $categories, category, name='tabs'}
                            {if $dwoo.foreach.tabs.first}
                                <li class="active"><a href="#tab{$dwoo.foreach.tabs.index}primary" data-toggle="tab">{$category.title}</a></li>
                            {else}
                                <li><a href="#tab{$dwoo.foreach.tabs.index}primary" data-toggle="tab">{$category.title}</a></li>
                            {/if}
                        {/foreach}
                        <!-- <li class="active"><a href="#tab1primary" data-toggle="tab">Primary 1</a></li>
                        <li><a href="#tab2primary" data-toggle="tab">Primary 2</a></li>
                        <li><a href="#tab3primary" data-toggle="tab">Primary 3</a></li>
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#tab4primary" data-toggle="tab">Primary 4</a></li>
                                <li><a href="#tab5primary" data-toggle="tab">Primary 5</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        {foreach $categories, category, name='tabs'}
                            <div class="tab-pane fade{if $dwoo.foreach.tabs.first} in active{/if}" id="tab{$dwoo.foreach.tabs.index}primary">
                                <table border="0">
                                    {foreach $category.settings setting}
                                        <tr>
                                            <td style="min-width: 150px; padding-top: 10px; padding-bottom: 10px; padding-right: 20px; " title="{$setting.description}"><b>{$setting.title}</b></td>
                                            <td>{$setting.code}</td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </div>
                        {/foreach}
                    </div>
                </div>
                <div class="panel-footer">
                    {if $permission_to_edit_settings == true}
                        <div class="pull-right">
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> {lang}Save{/lang}</button>
                        </div>
                    {/if}

                    <!-- <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> {lang}Discard{/lang}</button> -->
                    <br />
                    <span style="height: 10px; ">&nbsp;</span>
                </div>
            </form>
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->