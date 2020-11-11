$(function () 
{
    $("input[name='username']").focusout(function (e) { 
        const username = $(this).val();
        $.ajax({
            type: "POST",
            url: "/login/comprobarUsuario",
            data: {username:username},
            dataType: "JSON",
            success: function (response) {
                //? El usuario existe
                if (response == true) {                  

                        $.confirm({
                            title: '¡UPS!',
                            content: "Ese usuario ya existe, por favor elija otro.",
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                        $("input[name='username']").focus();
                                }
                            }
                        });
                }
                //? El usuario no existe
                else if (response == false) {



                }
                else {
                    console.log(response.status);

                    errorInesperado();
                }
            }
        });
    });
});

function errorInesperado()
{
    $.confirm({
        title: 'Error inesperado',
        content: 'Un error ocurrio al intentar buscar el usuario',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}
