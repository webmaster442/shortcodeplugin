jQuery(document).ready(function ($) {
    $('.easy-footnote a').qtip({
        position: {
            my: 'top center',  // Position my top left...
            at: 'bottom center', // at the bottom right of...
            //target: $('.container-post') // my target
            //viewport: $('.post_copy')
        },
        style: {
            classes: 'qtip-bootstrap'
        },
        show: {
            effect: function () { $(this).slideDown(); }
        },
        hide: {
            fixed: true,
            delay: 400,
            event: 'unfocus mouseleave',
            effect: function () { $(this).slideUp(); }
        }
    });
    $(".tablesorter").tablesorter();
    if (typeof protect !== 'undefined' && protect) {
        $('body').bind('copy cut', function (e) {
            e.preventDefault();
            return false;
        });
        $(document).on("contextmenu", function () {
            return false;
        });
    }
});