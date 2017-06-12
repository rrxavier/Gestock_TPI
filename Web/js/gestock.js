function addPopup(msg)
{
    $('body').append('<div class="popup"><div class="text-center"><h2>' + msg + '</h2></div></div>');

    $(".popup").slideDown();
    setTimeout(function() {
        $(".popup").slideUp();
    }, 2000);
}

function addToCart(idProduct, quantity = 1)
{
    $.ajax({
            method: 'POST',
            url: 'addToCart.php',
            data: {'idProduct': idProduct, 'quantity': quantity},
            dataType: 'html',
            success: function (data) {
                if (data == "NotConnected")
                    window.location.replace("login.php");
                else if (data == "NoProduct")
                    addPopup("Please select a product !");
                else if (data)
                    addPopup("Added to the cart !");
                else
                    addPopup("Error, try again.");
            },
            error: function(jqXHR) {
                alert(jqXHR.responseText + "" + jqXHR.status);
            }
    });
}