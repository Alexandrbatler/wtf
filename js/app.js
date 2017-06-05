'use strict';

$(function () {
    $('[href="#"]').click(function (e) {
        e.preventDefault();
    });

    $('.header__action').click(function (e) {
        let $this    = $(this);
        let formType = $this.attr('data-form');

        $('.main__forms')
            .find('form')
            .addClass('hide')
            .end()
            .find(`.main__form-${formType}`)
            .removeClass('hide');

        let text = 'Войти';
        formType = 'login';

        if ($this.text() === text) {
            text     = 'Регистрация';
            formType = 'reg';
        }

        $this
            .text(text)
            .attr('data-form', formType);
    });

    $('form').submit(function (e) {
        e.preventDefault();

        let formClass = this.className;
        let $form     = $(`.${formClass}`);
        let $errors   = $form.find('.error');
        let $inputs   = $form.find('.input');

        let formData       = new FormData($form[0]);
        let login          = formData.get('login');
        let email          = formData.get('email');
        let password       = formData.get('password');
        let passwordRepeat = formData.get('password-repeat');

        $form
            .find('input, button')
            .attr('disabled', 'disabled');
        $errors
            .addClass('hide')
            .text('');
        $inputs.removeClass('error__input');

        let isLogin = formClass.indexOf('login') !== -1;
        let isReg   = formClass.indexOf('reg') !== -1;

        let regExp;

        let isError     = false;
        let inputErrors = [];

        let loginErrText = [];
        if (login !== null) {
            if (isReg) {
                regExp = /^[a-z0-9]{4,}$/ig;

                if (!regExp.test(login)) {
                    loginErrText.push('Логин не может быть менее 4 символов и должен состоять из букв и цифр');
                }
            }

            if (isLogin) {
                regExp = /^\s*$/ig;

                if (regExp.test(login)) {
                    loginErrText.push('Данное поле обязательно для заполнения');
                }
            }

            if (loginErrText.length > 0) {
                isError = true;
                $form
                    .find('.error-login')
                    .text(loginErrText.join('. '));
                inputErrors.push('input__login');
            }
        }

        let passError = [];
        if (password !== null) {
            regExp = /^[a-z0-9]{6,}$/ig;

            if (passwordRepeat !== null) {
                if (regExp.test(password)) {
                    passError.push('Пароль не может быть менее 6 символов и состоять из букв и цифр');
                }

                if (password !== passwordRepeat) {
                    passError.push('Пароль и его подтверждение должны совпадать');
                }
            }

            regExp = /^\s*$/ig;
            if (regExp.test(password)) {
                passError.push('Данное поле обязательно для заполнения');
            }

            if (passError.length > 0) {
                isError = true;
                $form
                    .find('.error-password')
                    .text(passError.join('. '));
                inputErrors.push('input__password');
            }
        }

        let pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if (email !== null && !(pattern.test(email))) {
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

            $form
                .find('input, button')
                .removeAttr('disabled');

            return;
        }

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
        }).done(function (response) {
            if (response['status'] && response['status'] === 'success') {
                window.location.href = response['relocate'];
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
        }).always(function () {
            $form
                .find('input, button')
                .removeAttr('disabled');
        });
    });
});