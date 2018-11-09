<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/settings')">@lang('Settings')</a></li>
        <li class="uk-active"><span>@lang('Editor Formats')</span></li>
    </ul>
</div>

<div class="uk-margin-top" riot-view>

    @if($app->module('cockpit')->hasaccess('editorformats', 'manage'))
    <div class="uk-form uk-clearfix" show="{!loading}">

        <span class="uk-form-icon">
            <i class="uk-icon-filter"></i>
            <input type="text" class="uk-form-large uk-form-blank" ref="txtfilter" placeholder="@lang('Filter by name...')" onkeyup="{ updatefilter }">
        </span>

        <div class="uk-float-right">
            @if($app->module('cockpit')->hasaccess('editorformats', 'manage'))
            <a class="uk-button uk-button-primary uk-button-large" href="@route('/editor-formats/format')">
                <i class="uk-icon-plus-circle uk-icon-justify"></i> @lang('Add')
            </a>
            @endif
        </div>

    </div>
    @endif

    <div class="uk-text-xlarge uk-text-center uk-text-primary uk-margin-large-top" show="{ loading }">
        <i class="uk-icon-spinner uk-icon-spin"></i>
    </div>

    <div class="uk-text-large uk-text-center uk-margin-large-top uk-text-muted" show="{ !loading && formats.length == 0 }">
        <img class="uk-svg-adjust" src="@url('assets:app/media/icons/database.svg')" width="100" height="100" alt="@lang('Formats')" data-uk-svg />
        <p>@lang('No editor formats found')</p>
    </div>

    <div class="uk-grid uk-grid-match uk-grid-gutter uk-grid-width-1-1 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 uk-margin-top">

        <div each="{ format, name in formats }" show="{ infilter(format) }">

            <div class="uk-panel uk-panel-box uk-panel-card">
                <div class="uk-grid uk-grid-small">
                    @if($app->module('cockpit')->hasaccess('editorformats', 'manage'))
                    <div data-uk-dropdown="delay:300">
                        <a class="uk-icon-cog"" href="@route('/editor-formats/format')/{name}"></a>
                        <a class="uk-text-bold uk-flex-item-1 uk-text-center uk-link-muted" href="@route('/editor-formats/format')/{name}">{ name }</a>
                        <div class="uk-dropdown">
                            <ul class="uk-nav uk-nav-dropdown">
                                <li class="uk-nav-header">@lang('Actions')</li>
                                <li class="uk-nav-divider"></li>
                                <li><a href="@route('/editor-formats/format')/{name}">@lang('Edit')</a></li>
                                <li class="uk-nav-item-danger"><a class="uk-dropdown-close" onclick="{ parent.remove }">@lang('Delete')</a></li>
                            </ul>
                        </div>
                    </div>
                    @else
                    <span class="uk-text-large uk-display-block">{ name }</span>
                    @endif
                </div>

                <div class="uk-margin-top">
                    <div class="uk-margin-small-bottom">
                        <span class="uk-text-small uk-display-block">{ format.description }</span>
                    </div>
                    <div class="uk-margin-small-bottom">
                        <span class="uk-text-small uk-text-uppercase uk-text-muted">@lang('Menubar')</span>
                        <span if="{countEnabled(format.menubar)}" class="uk-text-small uk-display-block">{ countEnabled(format.menubar) } @lang('items')</span>
                        <span if="{!countEnabled(format.menubar)}" class="uk-text-small uk-display-block"> @lang('Disabled')</span>
                    </div>
                    <div class="uk-margin-small-bottom">
                        <span class="uk-text-small uk-text-uppercase uk-text-muted">@lang('Plugins')</span>
                        <span class="uk-text-small uk-display-block">{ countEnabled(format.plugins) }</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script type="view/script">

        var $this = this;

        this.ready  = false;
        this.formats = {{ json_encode($formats) }};

        remove(e, format) {
            format = e.item.format;

            App.ui.confirm("Are you sure?", function() {
                App.callmodule('editorformats:removeFormat', format.name).then(function(data) {
                    App.ui.notify("Format removed", "success");
                    delete $this.formats[format.name];
                    $this.update();
                });
            });
        }

        updatefilter(e) {
        }

        infilter(format, value, name, label) {
            if (!this.refs.txtfilter.value) {
                return true;
            }

            value = this.refs.txtfilter.value.toLowerCase();
            name  = [format.name.toLowerCase(), format.description.toLowerCase()].join(' ');

            return name.indexOf(value) !== -1;
        }

        countEnabled(plugins) {
            return Object.values(plugins).filter(function(item) {
                return item;
            }).length;
        }

    </script>


</div>
