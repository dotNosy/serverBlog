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
                                }
                            }
                        });
                        $("input[name='username']").css({
                            borderColor: 'red'
                          });

                }
                //? El usuario no existe
                else if (response == false) {

                    $("input[name='username']").css({
                        borderColor: 'rgb(206, 212, 218)'
                      });

                }
                else {
                    console.log(response.status);

                    errorInesperado();
                }
            }
        });
    });



    $("input[name='rpassword']").focusout(function (e) { 

        const password = $("input[name='password']").val();
        const rpassword = $(this).val();

        comprobarContraseñas(password, rpassword);
        
    });

    // $("input[name='password']").focusout(function (e) { 

    //     const password = $(this).val();
    //     const rpassword = $("input[name='rpassword']").val();

    //     comprobarContraseñas(password, rpassword);
        
    // });

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

function comprobarContraseñas(password, rpassword)
{
    $.ajax({
        type: "POST",
        url: "/login/comprobarContraseñaDiferente",
        data: {password:password, rpassword:rpassword},
        dataType: "JSON",
        success: function (response) {
            //? El usuario existe
            if (response == true) {                  

                    $.confirm({
                        title: '¡UPS!',
                        content: "Las contraseñas no coinciden.",
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });
                    $("input[name='rpassword']").css({
                        borderColor: 'red'
                      });
                      $("input[name='password']").css({
                        borderColor: 'red'
                      });

            }
            //? El usuario no existe
            else if (response == false) {

                $("input[name='rpassword']").css({
                    borderColor: 'rgb(206, 212, 218)'
                  });

                  $("input[name='password']").css({
                    borderColor: 'rgb(206, 212, 218)'
                  });

            }
            else {
                console.log(response.status);

                errorInesperado();
            }
        }
    });
}
