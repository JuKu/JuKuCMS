<!-- installed plugins -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{lang}Installed Plugins{/lang}</h3>
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
                                <td>${$plugin.title}<br /><small>{$plugin.description}</small></td>
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
                <h3 class="box-title">{lang}Not installed Plugins{/lang}</h3>
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
                                    {/foreach} | <a href="{$plugin.homepage}">Visit plugin homepage</a>
                                </td>
                                <td>{$plugin.version}</td>
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
