
<div class="page_menu dropdown pull-right">
    <a data-toggle="dropdown">Budget @icon('ellipsis-v')</a>
    <ul  class="dropdown-menu">
        <li><a href="{!! action('Budget\FoodController@edit', [$budget->id] ) !!}">Food policy</a></li>
        <li><a href="{!! action('Budget\LogsController@index', [$budget->id] ) !!}">Assignment log</a></li>
    </ul>
</div>
