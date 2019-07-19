var stripe = Stripe('pk_test_W2lQqUZ0BHapP3fyYddX35DJ009ZFdfKJt');


$(document).ready(function(){
    $('#pay').on('click', function(event){
        var ID = $('#pay').data("role");

        stripe.redirectToCheckout({
            // Make the id field from the Checkout Session creation API response
            // available to this file, so you can provide it as parameter here
            // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
            sessionId: ID
        }).then(function (result) {
            alert(result);
        });
    })
});