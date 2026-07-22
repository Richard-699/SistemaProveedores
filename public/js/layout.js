window.toggleMenu = function() {
    var sideNav = document.getElementById('side_nav');
    var contenido = document.getElementById('contenido');
    if (sideNav && contenido) {
        if (window.innerWidth <= 767) {
            sideNav.classList.toggle('active');
        } else {
            sideNav.classList.toggle('collapsed');
            contenido.classList.toggle('expanded');
        }
    } else {
        console.error("side_nav or contenido not found");
    }
};

jQuery(document).ready(function($) {
    $(".sidebar ul li").on('click', function() {
        $(".sidebar ul li.active").removeClass('active');
        $(this).addClass('active');
    });

    // Close menu when clicking outside on mobile
    $(document).on('click', function(e) {
        if (window.innerWidth <= 767) {
            if (!$(e.target).closest('#side_nav').length && !$(e.target).closest('#menu-toggle').length) {
                $('#side_nav').removeClass('active');
            }
        }
    });
});
