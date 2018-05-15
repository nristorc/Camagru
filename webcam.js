$(document).ready(function($){

    var $superposition_chat = $('#superp_photo_chat');

    var $superposition_chien = $('#superp_photo_chien');

    var $superposition_perroquet = $('#superp_photo_perroquet');

    var $superposition_licorne = $('#superp_photo_licorne');

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

    $superposition_licorne.click(function(e){
        e.preventDefault();
        superposition_licorne_funct();
    });

    function superposition_chat_funct() {
        $.post('webcamDataTest.php', {
            href: $superposition_chat.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }

    function superposition_licorne_funct() {
        $.post('webcamDataTest.php', {
            href: $superposition_licorne.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }

    function superposition_chien_funct() {
        $.post('webcamDataTest.php', {
            href: $superposition_chien.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }

    function superposition_perroquet_funct() {
        $.post('webcamDataTest.php', {
            href: $superposition_perroquet.data('href')

        }).done(function (data, textStatus, jqXHR) {
            console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }


});