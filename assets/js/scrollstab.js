;(function() {
    'use strict';
    
    
    $(activate);
    
    
    function activate() {
        
        $('.nav-tabs').scrollingTabs({
            enableSwiping: true
        })
        .on('ready.scrtabs', function() {
            $('.tab-content').show();
        });
        
    }
    
}());