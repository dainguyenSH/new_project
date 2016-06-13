$(document).ready(function() {
var timeout = 200;

// Logo

//Check Point
$("#checked-point").click( function(){
    if( $(this).is(':checked') ) {
        $('.form-point').show(timeout);
    } else {
        $('.form-point').hide(timeout);
    }
});

//Check Age
$("#checked-age").click( function(){
    if( $(this).is(':checked') ) {
        $('.form-age').show(timeout);
    } else {
        $('.form-age').hide(timeout);
    }
});

//Change Color
$(document).on('change','.background-color',function(){
   $(this).addClass('changed');
   $(this).closest('.input-group').find('.choice-color').val($(this).val());
})



$(document).on('keyup','.choice-color',function(){

    $(this).closest('.input-group').find('.background-color').val($(this).val());
    // $('#background-color').val($(this).val());
})

$(document).on('click','#reset-setting-color',function(){
    $(this).closest('.input-group').find('.background-color').val('#f94876');
    $(this).closest('.input-group').find('.choice-color').val('#f94876');

});

$(document).on('click','#reset-setting-text-color',function(){
    $(this).closest('.input-group').find('.text-color').val('#ffffff');
    $(this).closest('.input-group').find('.choice-text-color').val('#ffffff');

});

//Add new form
$(function() {
    // var scntDiv = $('#addInputs');
    // var i = $('#form').size() + 2;

    $('#addInputs').on('click', function() {
        var copy = $(".orgrion .elementCard").clone();
        $(".inforCardLevel").append(copy);
        $( ".inforCardLevel .elementCard" ).show();
        $(".currentcy").autoNumeric('init');  //autoNumeric with defaults
    });

    
    $('#addInputsAge').on('click', function() {
        var copy = $(".orgrion .elementAge").clone();
        $(".inforCardAge").append(copy);
        $( ".inforCardAge .elementAge" ).show();
         $(".currentcy").autoNumeric('init');  //autoNumeric with defaults
    });

    $(document).on('click','.removeCard',function(){
        $( this ).closest( ".commonCard" ).remove();
    });
});

// END new form

//Store Parner
$('#createPartner').click(function(e) {
    e.preventDefault();
    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( ".text-color" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );

    var cardsInfoLevel = [];
    var cardsInfoAge = [];

    var unitCard = $( ".unitCard" ).autoNumeric('get');
    if( $("#checked-point").is(':checked') ) {
        $( ".inforCardLevel .elementCard" ).each(function( index ) {
            var namecard = $( this ).find( ".namecard" ).val();
            var fromCard = $( this ).find( ".fromCard" ).autoNumeric('get');
            var toCard = $( this ).find( ".toCard" ).autoNumeric('get');
            var bonuscard = $( this ).find( ".bonuscard" ).autoNumeric('get');
            var backgroundCard = $( this ).find( ".backgroundCard" ).val();
            var textCard = $( this ).find( ".textCard" ).val();

            cardsInfoLevel.push([namecard, fromCard, toCard, bonuscard, backgroundCard, textCard]);
        });
    }

    if( $("#checked-age").is(':checked') ) {
        $( ".inforCardAge .elementAge" ).each(function( index ) {
            var namecard = $( this ).find( ".namecard" ).val();
            var fromCard = $( this ).find( ".fromCard" ).autoNumeric('get');
            var toCard = $( this ).find( ".toCard" ).autoNumeric('get');
            var bonuscard = $( this ).find( ".bonuscard" ).autoNumeric('get');
            var backgroundCard = $( this ).find( ".backgroundCard" ).val();
            var textCard = $( this ).find( ".textCard" ).val();

            cardsInfoAge.push([namecard, fromCard, toCard, bonuscard, backgroundCard, textCard]);
        });
    }
    
    // return false;
    

    $.confirm({
        theme: 'supervan',
        title: 'THÊM MỚI ĐỐI TÁC',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn tạo mới đối tác?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : 'store-partner',
                method : 'post',
                data : {
                    level:cardsInfoLevel, 
                    age:cardsInfoAge, 
                    merchantName:merchantName, 
                    backgroundColor:backgroundColor,
                    textColor:textColor,
                    merchantLogo:merchantLogo, 
                    unit:unitCard, 
                },
                success : function(data) {

                    $.endload();
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.messages
                    });
                    if (data.success == true) {
                        var host = window.location.protocol+"//"+window.location.hostname+"/admincp/partner";
                        window.location = host;
                    }
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){     
        },
    });
});


//Update Parner
$('#updatePartner').click(function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( ".text-color" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );
    var currentLogo = $( "#checkLogoExist" ).val();
    var nowLogo = $( '#currentLogo' ).val();
    var active = $( "#changeStatus" ).val();

    var cardsInfoLevel = [];
    var cardsInfoAge = [];

    var unitCard = $( ".unitCard" ).autoNumeric('get');
    if( $("#checked-point").is(':checked') ) {
        $( ".inforCardLevel .elementCard" ).each(function( index ) {
            var namecard = $( this ).find( ".namecard" ).val();
            var fromCard = $( this ).find( ".fromCard" ).autoNumeric('get');
            var toCard = $( this ).find( ".toCard" ).autoNumeric('get');
            var bonuscard = $( this ).find( ".bonuscard" ).autoNumeric('get');
            var backgroundCard = $( this ).find( ".backgroundCard" ).val();
            var textCard = $( this ).find( ".textCard" ).val();

            cardsInfoLevel.push([namecard, fromCard, toCard, bonuscard, backgroundCard, textCard]);
        });
    }

    if( $("#checked-age").is(':checked') ) {
        $( ".inforCardAge .elementAge" ).each(function( index ) {
            var namecard = $( this ).find( ".namecard" ).val();
            var fromCard = $( this ).find( ".fromCard" ).autoNumeric('get');
            var toCard = $( this ).find( ".toCard" ).autoNumeric('get');
            var bonuscard = $( this ).find( ".bonuscard" ).autoNumeric('get');
            var backgroundCard = $( this ).find( ".backgroundCard" ).val();
            var textCard = $( this ).find( ".textCard" ).val();

            cardsInfoAge.push([namecard, fromCard, toCard, bonuscard, backgroundCard, textCard]);
        });
    }
    
    // return false;
    

    $.confirm({
        theme: 'supervan',
        title: 'Cập nhật đối tác',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn sửa cấu hình thẻ?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : window.location.protocol+"//"+window.location.hostname+"/admincp/update-partner",
                method : 'post',
                data : {
                    id:id,
                    currentLogo:currentLogo,
                    nowLogo:nowLogo,
                    level:cardsInfoLevel, 
                    age:cardsInfoAge, 
                    merchantName:merchantName, 
                    backgroundColor:backgroundColor,
                    textColor:textColor,
                    merchantLogo:merchantLogo, 
                    unit:unitCard,
                    active:active, 
                },
                success : function(data) {

                    $.endload();
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.messages
                    });
                    if (data.success == true) {
                        var host = window.location.protocol+"//"+window.location.hostname+"/admincp/partner";
                        window.location = host;
                    }
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){     
        },
    });
});

//Add new Partner
$("#addNewMerchant").click(function(e){
    e.preventDefault();

    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( "#text-color" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );

    $.confirm({
        theme: 'supervan',
        title: 'THÊM MỚI MERCHANT',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn tạo mới merchant?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : 'add-new-merchant',
                method : 'post',
                data : {
                    merchantName : merchantName,
                    backgroundColor : backgroundColor,
                    textColor : textColor,
                    merchantLogo : merchantLogo

                },
                success : function(data) {
                    $.endload();
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.messages
                    });

                    if (data.success == true) {
                        var host = window.location.protocol+"//"+window.location.hostname+"/admincp/new-merchant";
                        window.location = host;
                    }
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){
            
        },
    });
 });


//Add Boo Merchant
//Add new Partner
$("#addBooMerchant").click(function(e){
    e.preventDefault();

    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( "#text-color" ).val();
    var desc = $( "#boo-card-option" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );

    $.confirm({
        theme: 'supervan',
        title: 'THÊM MỚI BOO MERCHANT',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn tạo mới Boo Merchant?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : 'add-boo-merchant',
                method : 'post',
                data : {
                    merchantName : merchantName,
                    backgroundColor : backgroundColor,
                    textColor : textColor,
                    merchantLogo : merchantLogo,
                    desc:desc,

                },
                success : function(data) {
                    $.endload();
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.messages
                    });
                    if (data.success == true) {
                        var host = window.location.protocol+"//"+window.location.hostname+"/admincp/boo";
                        window.location = host;
                    }
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){
            
        },
    });
 });


//Update new Partner
$("#updateNewMerchant").click(function(e){
    e.preventDefault();

    var id = $(this).data('id');
    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( "#text-color" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );
    var currentLogo = $( "#checkLogoExist" ).val();
    var nowLogo = $("#nowLogo").val();
    var active = $( "#changeStatus" ).val();

    $.confirm({
        theme: 'supervan',
        title: 'CẬP NHẬT MERCHANT',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn muốn chỉnh sửa thông tin merchant?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : window.location.protocol+"//"+window.location.hostname+"/admincp/update-new-merchant",
                method : 'post',
                data : {
                    id:id,
                    merchantName : merchantName,
                    backgroundColor : backgroundColor,
                    textColor : textColor,
                    merchantLogo : merchantLogo,
                    nowLogo:nowLogo,
                    currentLogo:currentLogo,
                    active:active,

                },
                success : function(data) {
                    $.endload();
                    if (data.success == true) {
                        BootstrapDialog.show({
                            title: data.title,
                            message: data.messages
                        });
                        var URL = window.location.protocol+"//"+window.location.hostname+"/admincp/new-merchant";
                        setTimeout(function(){ window.location = URL; }, 1000);
                    }
                },
                error : function (xhr){
                    $.endload();
                    var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    $(location).attr('href', host);
                },
            });
        },
        cancel: function(){
            
        },
    });
 });


 //Update Boo Partner
$("#updateBooMerchant").click(function(e){
    e.preventDefault();

    var id = $(this).data('id');
    var merchantName = $( ".merchantName" ).val();
    var backgroundColor = $( "#background-color" ).val();
    var textColor = $( "#text-color" ).val();
    var merchantLogo = $( ".img-logo" ).attr( "src" );
    var currentLogo = $( "#checkLogoExist" ).val();
    var nowLogo = $("#nowLogo").val();
    var desc = $( "#boo-card-option" ).val();
    var active = $( "#changeStatus" ).val();

    $.confirm({
        theme: 'supervan',
        title: 'CHỈNH SỬA MERCHANT',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắn muốn thay đổi thông tin merchant?",
        confirm: function(){
            $.loadding();
            $.ajax({
                url : window.location.protocol+"//"+window.location.hostname+"/admincp/update-boo-merchant",
                method : 'post',
                data : {
                    id:id,
                    merchantName : merchantName,
                    backgroundColor : backgroundColor,
                    textColor : textColor,
                    merchantLogo : merchantLogo,
                    nowLogo:nowLogo,
                    currentLogo:currentLogo,
                    desc:desc,
                    active:active,

                },
                success : function(data) {
                    if ( data.success == true ) {
                        $.endload();
                        BootstrapDialog.show({
                            title: data.title,
                            message: data.messages
                        });

                        var URL = window.location.protocol+"//"+window.location.hostname+"/admincp/boo";
                        setTimeout(function(){ window.location = URL; }, 1000);
                    }
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){
            
        },
    });
 });
/*Add new catagory Event*/
// $("#newMerchant").submit(function(e) {
//     e.preventDefault();

//     // var url = "path/to/your/script.php"; // the script where you handle the form input.

//     $.ajax({
//            method: "POST",
//            url: 'add-new-merchant',
//            data: $("#newMerchant").serialize(), // serializes the form's elements.
//            success: function(data)
//            {
//                alert(data); // show response from the php script.
//            }
//          });

//     // e.preventDefault(); // avoid to execute the actual submit of the form.
// });



});
