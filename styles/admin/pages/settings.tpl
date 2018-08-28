<div class="row">
    <div class="col-xs-12">
        <!-- see also https://bootsnipp.com/snippets/featured/panels-with-nav-tabs -->
        <div class="panel with-nav-tabs panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    {foreach $categories, category, name='tabs'}
                        {if $dwoo.foreach.default.first}
                            <li class="active"><a href="#tab{$dwoo.foreach.default.index}primary" data-toggle="tab">{$category.title}</a></li>
                        {else}
                            <li><a href="#tab2primary" data-toggle="tab">Primary 2</a></li>
                        {/if}
                    {/foreach}
                    <li class="active"><a href="#tab1primary" data-toggle="tab">Primary 1</a></li>
                    <li><a href="#tab2primary" data-toggle="tab">Primary 2</a></li>
                    <li><a href="#tab3primary" data-toggle="tab">Primary 3</a></li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#tab4primary" data-toggle="tab">Primary 4</a></li>
                            <li><a href="#tab5primary" data-toggle="tab">Primary 5</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1primary">Primary 1</div>
                    <div class="tab-pane fade" id="tab2primary">Primary 2</div>
                    <div class="tab-pane fade" id="tab3primary">Primary 3</div>
                    <div class="tab-pane fade" id="tab4primary">Primary 4</div>
                    <div class="tab-pane fade" id="tab5primary">Primary 5</div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->