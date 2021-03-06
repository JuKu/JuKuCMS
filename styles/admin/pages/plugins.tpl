{if empty($installed_plugins) == false}
<!-- installed plugins -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Installed Plugins{/lang}</strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{lang}Plugin{/lang}</th>
                            <th>{lang}Title / Description{/lang}</th>
                            <th>{lang}Installed Version{/lang}</th>
                            <th>{lang}License{/lang}</th>
                            <th>{lang}Actions{/lang}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $installed_plugins plugin}
                        <tr>
                            <td>
                                {$plugin.name}<br />

                                <!-- check plugin compatibility -->
                                {if $plugin.compatible === true}
                                    <span class="label label-success">compatible</span>
                                {else}
                                    <span class="label label-danger">incompatible</span>
                                {/if}
                            </td>
                            <td>
                                <strong style="color: /*#4096EE*/#3F4C6B; ">{$plugin.title}</strong><br /><small>{$plugin.description}</small><br /><br />

                                By {foreach $plugin.authors key author name='plugins'}
                                    {if $dwoo.foreach.plugins.index > 0}, {/if}<a href="{$author.homepage}" target="_blank" title="{$author.role}">{$author.name}</a>
                                {/foreach} | <a href="{$plugin.homepage}" target="_blank">Visit plugin homepage</a><br />

                                <!-- support information -->
                                {foreach $plugin.support_links key link name='supportlinks'}
                                    {if $dwoo.foreach.supportlinks.index == 0}
                                        <span style="color: #008C00; ">Support: </span>
                                    {/if}

                                    {if $dwoo.foreach.supportlinks.index > 0} | {/if}<a href="{$link.href}" target="_blank">{$link.title}</a>
                                {/foreach}
                            </td>
                            <td>
                                {if $plugin.uptodate === true}
                                    <span style="color: #008C00; "><b>{$plugin.version}</b></span><br />
                                {else}
                                    <span style="color: #D01F3C; "><b>{$plugin.version}</b></span><br />
                                {/if}

                                {if $plugin.alpha}
                                    <span class="label label-danger">{lang}alpha{/lang}</span>
                                {elseif $plugin.beta}
                                    <span class="label label-warning">{lang}beta{/lang}</span>
                                {else}
                                    <span class="label label-info">{lang}release{/lang}</span>
                                {/if}
                            </td>
                            <td>{$plugin.license}</td>
                            <td>
                                <!-- check if plugin upgrade is available -->
                                {if $plugin.upgrade_available == true}
                                    <a href="{$BASE_URL}/admin/plugin_installer?plugin={$plugin.name}&amp;action=upgrade" class="btn btn-success">{lang}update{/lang}</a>
                                {/if}

                                <!-- link to install plugin -->
                                <a href="{$BASE_URL}/admin/plugin_installer?plugin={$plugin.name}&amp;action=uninstall" class="btn btn-danger">{lang}uninstall{/lang}</a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>{lang}Plugin{/lang}</th>
                        <th>{lang}Title / Description{/lang}</th>
                        <th>{lang}Version{/lang}</th>
                        <th>{lang}License{/lang}</th>
                        <th>{lang}Actions{/lang}</th>
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
{/if}

{if empty($plugins) == false}
<!-- not installed plugins -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Not installed Plugins{/lang}</strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="plugintable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{lang}Plugin{/lang}</th>
                            <th>{lang}Title / Description{/lang}</th>
                            <th>{lang}Version{/lang}</th>
                            <th>{lang}License{/lang}</th>
                            <th>{lang}Actions{/lang}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $plugins plugin}
                            <tr>
                                <td>
                                    {$plugin.name}<br />

                                    <!-- check plugin compatibility -->
                                    {if $plugin.compatible === true}
                                        <span class="label label-success">compatible</span>
                                    {else}
                                        <span class="label label-danger">incompatible</span>
                                    {/if}
                                </td>
                                <td>
                                    <strong style="color: /*#4096EE*/#3F4C6B; ">{$plugin.title}</strong><br /><small>{$plugin.description}</small><br /><br />

                                    By {foreach $plugin.authors key author name='plugins'}
                                        {if $dwoo.foreach.plugins.index > 0}, {/if}<a href="{$author.homepage}" target="_blank" title="{$author.role}">{$author.name}</a>
                                    {/foreach} | <a href="{$plugin.homepage}" target="_blank">Visit plugin homepage</a><br />

                                    <!-- support information -->
                                    {foreach $plugin.support_links key link name='supportlinks'}
                                        {if $dwoo.foreach.supportlinks.index == 0}
                                            <span style="color: #008C00; ">Support: </span>
                                        {/if}

                                        {if $dwoo.foreach.supportlinks.index > 0} | {/if}<a href="{$link.href}" target="_blank">{$link.title}</a>
                                    {/foreach}
                                </td>
                                <td>
                                    {if $plugin.uptodate === true}
                                        <span style="color: #008C00; "><b>{$plugin.version}</b></span><br />
                                    {else}
                                        <span style="color: #D01F3C; "><b>{$plugin.version}</b></span><br />
                                    {/if}

                                    {if $plugin.alpha}
                                        <span class="label label-danger">{lang}alpha{/lang}</span>
                                    {elseif $plugin.beta}
                                        <span class="label label-warning">{lang}beta{/lang}</span>
                                    {else}
                                        <span class="label label-info">{lang}release{/lang}</span>
                                    {/if}
                                </td>
                                <td>{$plugin.license}</td>
                                <td>
                                    <!-- check plugin compatibility -->
                                    {if $plugin.compatible === true}
                                        <!-- link to install plugin -->
                                        <a href="{$BASE_URL}/admin/plugin_installer?plugin={$plugin.name}&amp;action=install" class="btn btn-success">{lang}install{/lang}</a>
                                    {else}
                                        <span class="label label-danger">incompatible</span>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{lang}Plugin{/lang}</th>
                            <th>{lang}Title / Description{/lang}</th>
                            <th>{lang}Version{/lang}</th>
                            <th>{lang}License{/lang}</th>
                            <th>{lang}Actions{/lang}</th>
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
{/if}