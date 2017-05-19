'use strict';

$(function () {
    $('[href="#"]').click(function (e) {
        e.preventDefault();
    });

    $('form').submit(function (e) {
        e.preventDefault();

        let $form = $(e.target);
        let $errors = $form.find('.error');
        let $inputs = $form.find('.input');

        $errors
            .addClass('hide')
            .text('');
        $inputs.removeClass('error__input');

        let formData = new FormData($form[0]);

        let isError        = false;
        let inputErrors    = [];

        if (formData.get('login') !== undefined && formData.get('login').length < 4) {
            isError = true;
            $form
                .find('.error-login')
                .text('Логин не может быть менее 3 символов');
            inputErrors.push('input__login');
        }

        let passError = '';
        if (formData.get('password') !== undefined  && formData.get('password').length < 6) {
            isError = true;
            passError += 'Пароль не может быть менее 6 символов. ';
            inputErrors.push('input__password');
        }
        if (formData.get('password-repeat') !== undefined && formData.get('password') !== formData.get('password-repeat')) {
            isError = true;
            passError += 'Пароль и его подтверждение должны совпадать';
            inputErrors.push('input__password');
        }
        $form
            .find('.error-password')
            .text(passError);

        let pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if (formData.get('email') !== undefined && !(pattern.test(formData.get('email')))) {
            isError = true;
            $form
                .find('.error-email')
                .text('Введите корректный email');
            inputErrors.push('input__email');
        }

        if (isError) {
            $errors.removeClass('hide');
            inputErrors.map(function (className) {
                $form
                    .find(`.${className}`)
                    .addClass('error__input');
            });

            return;
        }

        $.ajax({
            url: $form.attr('href'),
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

                    $form
                        .find('[name="' + name + '"]')
                        .addClass('error__input')
                        .siblings('.error')
                        .removeClass('hide')
                        .text(messages);
                });
            }
        });
    });
});