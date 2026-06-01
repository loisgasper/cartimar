jQuery(document).ready(function($) {
    
    // Get stores data
    var storesData = JSON.parse($('#storesData').text());
    var selectedCategories = [];
    var searchQuery = '';
    
    /**
     * Filter stores based on selected categories and search query
     */
    function filterStores() {
        $('.store-item').each(function() {
            var $item = $(this);
            var storeCategories = JSON.parse($item.attr('data-categories'));
            var storeName = $item.attr('data-store-name').toLowerCase();
            
            // Check category filter
            var categoryMatch = selectedCategories.length === 0 || 
                               selectedCategories.some(catId => storeCategories.includes(parseInt(catId)));
            
            // Check search filter
            var searchMatch = searchQuery === '' || storeName.includes(searchQuery.toLowerCase());
            
            if (categoryMatch && searchMatch) {
                $item.show();
                // Also show corresponding marker
                $(`[data-store-id="${$item.attr('data-store-id')}"]`).addClass('visible');
            } else {
                $item.hide();
                // Hide corresponding marker
                $(`[data-store-id="${$item.attr('data-store-id')}"]`).removeClass('visible');
            }
        });
        
        // Show no results message if all stores are hidden
        var visibleCount = $('.store-item:visible').length;
        if (visibleCount === 0) {
            var noResultsMsg = '<p class="no-results-found">' + 
                              'No stores match your filters. Try adjusting your search.' +
                              '</p>';
            if ($('.no-results-found').length === 0) {
                $('#storesList').append(noResultsMsg);
            }
        } else {
            $('.no-results-found').remove();
        }
    }
    
    /**
     * Category checkbox filter
     */
    $('.store-category-filter').on('change', function() {
        selectedCategories = $('.store-category-filter:checked').map(function() {
            return $(this).val();
        }).get();
        
        filterStores();
    });
    
    /**
     * Store search input
     */
    $('#store-search').on('keyup', function() {
        searchQuery = $(this).val();
        filterStores();
    });
    
    /**
     * Store item hover - highlight corresponding marker on map
     */
    $('.store-item').on('mouseenter', function() {
        var storeId = $(this).attr('data-store-id');
        var $marker = $(`[data-store-id="${storeId}"]`);
        
        $marker.addClass('active');
        $(this).addClass('active');
    }).on('mouseleave', function() {
        var storeId = $(this).attr('data-store-id');
        var $marker = $(`[data-store-id="${storeId}"]`);
        
        $marker.removeClass('active');
        $(this).removeClass('active');
    });
    
    /**
     * Map marker hover - highlight corresponding store item in list
     */
    $('.store-marker').on('mouseenter', function() {
        var storeId = $(this).attr('data-store-id');
        var $item = $(`[data-store-id="${storeId}"]`);
        
        $(this).addClass('active');
        if ($item.is(':visible')) {
            $item.addClass('active');
            // Scroll to the item
            var $container = $('#storesList');
            var itemPosition = $item.position().top;
            var containerHeight = $container.height();
            var itemHeight = $item.height();
            
            if (itemPosition < 0 || itemPosition + itemHeight > containerHeight) {
                $container.scrollTop($container.scrollTop() + itemPosition - 50);
            }
        }
    }).on('mouseleave', function() {
        var storeId = $(this).attr('data-store-id');
        var $item = $(`[data-store-id="${storeId}"]`);
        
        $(this).removeClass('active');
        $item.removeClass('active');
    });
    
    /**
     * Show all markers by default
     */
    $('.store-marker').addClass('visible');
    
    /**
     * Get coordinates from floor plan click (for admin use)
     */
    $('#floorPlanImage').on('click', function(e) {
        // Only useful if we want to add this functionality to admin
        // var rect = this.getBoundingClientRect();
        // var x = e.clientX - rect.left;
        // var y = e.clientY - rect.top;
        // console.log('Clicked at: ' + x + ', ' + y);
    });
    
});
