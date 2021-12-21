<?php $menu = App\Utilities\Menu::make('menu.php'); ?>
<div id="js-app-menu" class="app-menu" style="display: none;">
    <div class="app-menu__content">
        <button class="btn-close">&times;</button>
        <div class="app-menu__menu">
            @foreach($menu as $title => $items)

                <div class="app-menu__submenu">
                    <h3>{{ $title }}</h3>

                    @foreach($items as $name => $href)

                        <a href="{{ $href }}">{{ $name }}</a>

                    @endforeach

                </div>

            @endforeach
        </div>
    </div>
</div>
