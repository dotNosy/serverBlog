$(function () 
{
    //FAVS
    $("button[name='type']").click(function (e) { 
        const btnPressed = $(this);
        const id = $(this).siblings("input").val();
        const type = $(btnPressed).val();

        $.ajax({
            type: "POST",
            url: "/post/addFavoritesOrFeed",
            data: {type:type, id:id},
            dataType: "JSON",
            success: function (response) {
                if (response.error !== undefined && response.error != "") {
                    console.log(response.error);
                    $.confirm({
                        title: '¡Error!',
                        content: response.error,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });
                }
                else if (response.status == true) {
                    switch (type) {
                        case "favoritos":
                            $(btnPressed).toggleClass("btn-outline-danger btn-danger");
                        break;

                        case "feed":
                            $(btnPressed).toggleClass("btn-outline-primary btn-primary");
                        break;
                    }
                }
                else {
                    errorInesperado(type);
                }
            }
            ,error: function (error) {
                console.log(error);
                errorInesperado(type);
            }
        });
    });
});


function errorInesperado(type)
{
    $.confirm({
        title: 'Error inesperado',
        content: 'Un error ocurrio al intentar añadir a ' + type,
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}


