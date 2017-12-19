
   
    // var selector = '.nav li';
    //     $(selector).on('click', function () {
    //         $(selector).removeClass('active');
    //         $(this).addClass('active1');
    //     });

    // $('#links a').click(function (e) {
    //     console.log("Hello");
    //         e.preventDefault();
    //         $('#links a').removeClass('active');
    //         $(this).addClass('active');
    //     });


    // function active(e) {
    //         $('.active').each(function (i) {
    //         i.removeClass('active');
    //         });
    //         e.addClassName('active');
    //     };
    
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () { scrollFunction() };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    $(document).ready(function () {
            $(".links").click(function () {
            $(".links").removeClass("active_link");
            $(this).addClass("active_link");
        });
    });

    //   $('#modal_main').modal({
    //         show: false,
    //         backdrop: 'static',
    //         keyboard: false
    //     })

    //     function modal_open() {
    //         $("#modal_main").show();
    //     }
   