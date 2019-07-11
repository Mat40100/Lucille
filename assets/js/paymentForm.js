$(document).ready(function(){
    var stripe = Stripe('pk_test_W2lQqUZ0BHapP3fyYddX35DJ009ZFdfKJt');

    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var cardholderName = document.getElementById('cardholder-name');
    var cardButton = document.getElementById('card-button');

    var clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', function(ev) {
        stripe.handleCardPayment(
            clientSecret, cardElement, {
                payment_method_data: {
                    billing_details: {name: cardholderName.value}
                }
            }
        ).then(function(result) {
            if (result.error) {
                console.log(result.error)
            } else {
                console.log('Paiement réussi');
            }
        });
    });
});