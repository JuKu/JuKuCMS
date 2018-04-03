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
                            //TODO: add code here
                        {else}
                            <li><a href="{$item.href}"><i class="fa {$item.icon_class}"></i> {$item.title}</a></li>
                        {/if}
                    {/foreach}
                    <!-- <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                    <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li> -->
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