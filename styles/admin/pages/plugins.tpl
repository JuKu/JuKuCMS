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
                                <td>{$plugin.name}</td>
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
                                <td>{$plugin.installed_version}</td>
                                <td>{$plugin.license}</td>
                                <td>&nbsp;</td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{lang}Plugin{/lang}</th>
                            <th>{lang}Title / Description{/lang}</th>
                            <th>{lang}Installed Version{/lang}</th>
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
                                <td>{$plugin.name}</td>
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
                                <td><span style="color: #D01F3C; ">{$plugin.version}</span></td>
                                <td>{$plugin.license}</td>
                                <td>&nbsp;</td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{lang}Plugin{/lang}</th>
                            <th>{lang}Title / Description{/lang}</th>
                            <th>{lang}Installed Version{/lang}</th>
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
