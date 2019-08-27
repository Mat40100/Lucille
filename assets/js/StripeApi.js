var stripe = Stripe('pk_test_W2lQqUZ0BHapP3fyYddX35DJ009ZFdfKJt');

$(document).ready(function () {
    let $OnlinePaymentButton = $("#OnlinePaymentButton");
    let loadingGif = $('#ajax-loading');

    loadingGif.hide();

    $OnlinePaymentButton.on('click', function(){

        $OnlinePaymentButton.hide();
        loadingGif.show();

        var productId = $OnlinePaymentButton.data('product');

        $.ajax({
            url : '/pay/product/' + productId,
            type: 'GET',
            success : function(code_html, statut){
                loadingGif.hide();
                $OnlinePaymentButton.replaceWith(code_html);

                $('#pay').on('click', function(event){
                    var ID = $('#pay').data("role");

                    stripe.redirectToCheckout({
                        // Make the id field from the Checkout Session creation API response
                        // available to this file, so you can provide it as parameter here
                        // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                        sessionId: ID
                    }).then(function (result) {
                        console.log(result);
                    });
                })
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
                console.log("Ajax error");
            }
        });
    });
});

