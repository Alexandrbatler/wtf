'use strict';

$(function () {
    $('a[href="#"]').click(function (e) {
        e.preventDefault();
    });

    $('.btn-registry').click(function (e) {
        let $errors = $('.error');
        let $inputs = $('.input');

        $errors
            .addClass('hide')
            .text('');
        $inputs.removeClass('error__input');

        let formData = new FormData($(this).parent('form')[0]);

        let login          = formData.get('login');
        let email          = formData.get('email');
        let password       = formData.get('password');
        let passwordRepeat = formData.get('password-repeat');
        let isError        = false;
        let inputErrors    = [];

        if (login.length < 4) {
            isError = true;
            $('.error-login').text('Логин не может быть менее 3 символов.');
            inputErrors.push('input__login');
        }

        let passError = '';
        if (password.length < 6) {
            isError = true;
            passError += 'Пароль не может быть менее 6 символов. ';
            inputErrors.push('input__password');
        }
        if (password !== passwordRepeat) {
            isError = true;
            passError += 'Пароль и его повтор должны совпадать.';
            inputErrors.push('input__password');
        }
        $('.error-password').text(passError);

        if (!(/^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i.test(email))) {
            isError = true;
            $('.error-email').text('Введите корректный email');
            inputErrors.push('input__email');
        }

        if (isError) {
            $errors.removeClass('hide');
            inputErrors.map(function (className) {
                $(`.${className}`).addClass('error__input');
            });

            return;
        }

        $.ajax({
            url: '/include/Scripts/reg.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
        }).done(function (response) {
            if (response === 'success') {
                window.location.href = '/profile.php';
            } else if (response['errors'] && response['errors'].length !== 0) {
                let errors = response['errors'];
                Object.keys(errors).map(function (name) {
                    let messages = errors[name].join('. ');

                    $('[name="' + name + '"]')
                        .addClass('error__input')
                        .siblings('.error')
                        .removeClass('hide')
                        .text(messages);
                });
            }
        });
    });
});