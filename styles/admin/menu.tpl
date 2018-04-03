<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>

    {foreach $menu_array menu}
        {if $menu.has_submenus == true}
            {* menu with submenus *}
            <li class="treeview">
                <a href="#">
                    <i class="fa {$menu.icon_class}"></i> <span>{$menu.title}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {foreach $menu.submenus item}
                        {if $item.has_submenus == true}
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i> {$item.title}
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    {foreach $item.submenus submenu}
                                        <li><a href="{$submenu.href}"><i class="fa {$submenu.icon_class}"></i> {$submenu.title}</a></li>
                                    {/foreach}
                                    <!-- <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                            <span class="pull-right-container">
                                              <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                        </ul>
                                    </li> -->
                                </ul>
                            </li>
                        {else}
                            <li><a href="{$item.href}"><i class="fa {$item.icon_class}"></i> {$item.title}</a></li>
                        {/if}
                    {/foreach}
                </ul>
            </li>
        {else}
            {* menu without submenus *}
            <li>
                <a href="{$menu.href}">
                    <i class="fa {$menu.icon_class}"></i> <span>{$menu.title}</span>
                    <span class="pull-right-container">
                        <!-- <small class="label pull-right bg-green">Hot</small> -->

                        <!-- <small class="label pull-right bg-red">3</small>
                        <small class="label pull-right bg-blue">17</small> -->

                        {$menu.extension_code}
            </span>
                </a>
            </li>
        {/if}
    {/foreach}

</ul>