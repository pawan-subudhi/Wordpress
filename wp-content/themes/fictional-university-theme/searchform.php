<form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
    <label class="headline headline--medium" for="s">Perform a New Search:</label>
    <div class="search-form-row">
        <input class="s" placeholder="What are you looking for?" type="search" id="s" name="s" autocomplete="off">
        <input class="search-submit" type="submit" value="Search">
    </div>
</form>