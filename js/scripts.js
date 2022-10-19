(function($){
    //Inizializzazione carosello
    var owl = $('.incredible_post_slider').owlCarousel({
        //margin: 10,
        center: true,
        merge: true,
        loop: true,
        mergeFit: true,
        dots: false,
        responsive : {
            //From 0 up
            0 : {
                items: 1,
            }
        }
    });

    /*
    
    Per inserire 3 slides visibili contemporanemante,
    sostituire la proprietà responsive con:

    responsive : {
        //From 0 up
        0 : {
            items: 3,
        },
        //From 768 up
        768 : {
            items: 5,
        }
    }
    
    */
    
    //Gestione controlli custom (Next)
    $('.customNextBtn').click(function() {
        owl.trigger('next.owl.carousel');
    })
    //Gestione controlli custom (Prev)
    $('.customPrevBtn').click(function() {
        owl.trigger('prev.owl.carousel');
    })
}(jQuery));