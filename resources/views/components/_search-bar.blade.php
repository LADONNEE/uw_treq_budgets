<div class="container-fluid">
    @include('components/_search-close-button')
    <form method="get" action="{{ $action }}">
        <div class="search-input-box">
            <label for="search_bar_input" style="display:none;">Search query</label>
            <input type="text" class="search-input" name="q" id="search_bar_input" placeholder="Search..." />
        </div>
        <button type="submit" class="search-submit-button">Go</button>
    </form>
</div>
