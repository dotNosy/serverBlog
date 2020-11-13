$(function () {
    setInterval(function() {
        $.ajax({
            type: "post",
            url: "/profile/notificationsTimer",
            data: {},
            dataType: "json",
            success: function (response) {
                console.log(response.count);
                $("#notCount").text(response.count);

                if (response.count > 0) {
                    $("#notCount").removeClass("badge-primary");
                    $("#notCount").addClass("badge-danger");
                }
                else {
                    $("#notCount").addClass("badge-primary");
                    $("#notCount").removeClass("badge-danger");
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }, 5000);

    $(".showNotifications").click(function (e) { 
        $.ajax({
            type: "post",
            url: "/profile/notifications",
            data: {},
            dataType: "json",
            success: function (response) {
                $("#notificaciones-content").empty();
                for (let i = 0; i < response.notificaciones.length; i++) {
                    const element = response.notificaciones[i];
                    // console.log(element);
                    $("#notificaciones-content").append(element);
                    $("#notCount").text("0");
                    $("#notCount").removeClass("badge-danger");
                    $("#notCount").addClass("badge-primary");
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#notificaciones-content").on("click", ".deleteNotifications", function (e) {
        const id = $(this).attr("id");

        $.ajax({
            type: "post",
            url: "/profile/deleteNotificationsByNotificationID",
            data: {id:id},
            dataType: "json",
            success: function (response) {
                if (response.status == true) {
                    $("#notCount").text("0");
                    $("#notCount").removeClass("badge-danger");
                    $("#notCount").addClass("badge-primary");
                }
                else if (response.error != "") {
                    errorBorrar(response.error);
                }
                else {
                    errorBorrar("Error al eliminar las notificaciones.");
                }
            },
            error: function (error) {
                errorBorrar(error);
            }
        });
    });

    $("#borrarTodos").on("click", function (e) {
        $.ajax({
            type: "post",
            url: "/profile/deleteNotificationsByUserID",
            data: {},
            dataType: "json",
            success: function (response) {
                if (response.status == true) {
                    $('#staticBackdrop').modal('hide');
                    $("#notCount").val("0");
                    $("#notCount").toggleClass("badge-danger badge-primary");
                }
                else if (response.error != "") {
                    errorBorrar(response.error);
                }
                else {
                    errorBorrar("Error al eliminar las notificaciones.");
                }
            },
            error: function (error) {
                //errorBorrar(error);
                console.log(error);
            }
        });
    });
});

function errorBorrar($error) {
    $.confirm({
        title: 'Error inesperado',
        content: $error,
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}