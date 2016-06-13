$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

$("#menu-toggle-3").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

     $("#menu-toggle-2").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled-2");
        $('#menu ul').hide();
        $('.coppy-right').toggle();
        $('.title-togle').removeClass();

        $('.navbar').toggleClass('toggle-navbar');

    });

     
    // $(document).ready(function() {initMenu();});

