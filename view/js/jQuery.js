$(document).ready(function () {
    $('.buy-button').click(function () {
        var product_id = $(this).val();

        $.ajax({
            type: 'POST',
            url: './Dashboard.php',
            data: { product_id: product_id }, // Envia o ID do produto para o servidor
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                    })
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
