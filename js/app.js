'use strict';

$(function () {
    $('a[href="#"]').click(function (e) {
        e.preventDefault();
    });

    $('.btn-registry').click(function (e) {
        let formData = new FormData($(this).parent('form')[0]);

        $.ajax({
            url: '/include/Scripts/reg.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
        }).done(function (success) {
            console.log(success);
        });
    });
});