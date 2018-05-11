$(document).ready(function($){

    var $superposition_chat = $('#superp_photo_chat');

    var $superposition_chien = $('#superp_photo_chien');

    var $superposition_perroquet = $('#superp_photo_perroquet');

    $superposition_chat.click(function(e){
        e.preventDefault();
        superposition_chat_funct();

    });

    $superposition_chien.click(function(e){
        e.preventDefault();
        superposition_chien_funct();
    });

    $superposition_perroquet.click(function(e){
        e.preventDefault();
        superposition_perroquet_funct();
    });

    function superposition_chat_funct() {
        $.post('webcamData.php', {
            href: $superposition_chat.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }

    function superposition_chien_funct() {
        $.post('webcamData.php', {
            href: $superposition_chien.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }

    function superposition_perroquet_funct() {
        $.post('webcamData.php', {
            href: $superposition_perroquet.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }


});