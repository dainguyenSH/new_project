$(document).ready(function() {

    function filterAccountPage(page){


        var status = $('option:selected',$("#filter-account-status")).attr("data-status");

        var card_id = $('option:selected',$("#filter-account-type-card")).attr("data-card-id");
        var search_box = $.trim($("#account-search-box").val());

        if(status == 2) {
            // location.reload();
        }
        
        $.loadding();

        $.ajax({
            url: 'filter-account',
            // url: 'filter-account',
            method:'post',
            data : {
                "status" : status,
                "id" : card_id,
                "search_box" : search_box,
                "page" : page
            },
        
            success: function(data){
                $.endload();
                $(".list-customer-panel").empty();
                $('#account_paginator').empty();
                $(".list-customer-panel").append(data.result);
                
            },
            error: function(){},
        });
    }
    $("#filter-account-type-card").change(function(e) {
        
        e.preventDefault();
        filterAccountPage(0);
        
    });

    $("#filter-account-status").change(function(e) {
        e.preventDefault();
        filterAccountPage(0);
    });

    
    
    $("#account-search-box").change(function(e){
        e.preventDefault();
        filterAccountPage(0);
    });
});
