$(document).ready(function () {
    $('.rem_cart').click(function () {
        var product_id = $(this).val();

        $.ajax({
            type: 'POST',
            url: './Checkout.php',
            data: { rem_cart: product_id },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: 'Produto Removido do Carrinho!',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); 
                        }
                    });
                } else {
                    Swal.fire(
                        'Ops!',
                        'Erro ao remover do carrinho: ' + response.message,
                        'error'
                    );
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Ops!',
                    'Erro ao comunicar com o servidor: ' + error,
                    'error'
                );
            }
        });
    });
});
