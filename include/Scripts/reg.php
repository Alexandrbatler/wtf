<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}include/core.php";
require_once ROOT . 'include/Classes/Validator.class.php';

$errors = [];

$args = [
    'login'           => FILTER_SANITIZE_STRING,
    'password'        => FILTER_SANITIZE_STRING,
    'password-repeat' => FILTER_SANITIZE_STRING,
    'email'           => FILTER_SANITIZE_EMAIL,
];
$data = filter_var_array($_POST, $args);

$core = new Core();
if (!$core->db->connect()) {
    die(implode('\r\n', $core->getErrors()));
}

$rules = [
    'login' => [
        'min' => [
            'value' => 3,
            'message' => 'Длина логина не может быть менее 3 символов',
        ],
        'unique' => [
            'value' => $data['login'],
            'message' => 'Данный логин уже занят',
        ],
    ],
    'password' => [
        'min' => [
            'value' => 6,
            'message' => 'Длина пароля не может быть менее 6 символов',
        ],
        'equal' => [
            'value' => $data['password-repeat'],
            'message' => 'Пароль и его подтверждение должны совпадать',
        ],
    ],
    'email' => [
        'email' => [
            'value' => $data['email'],
            'message' => 'Введите корректный электронный адрес',
        ],
    ],
];

$validator = new Validator($data, $rules);
$results = $validator->validate();

echo json_encode($results, JSON_PRETTY_PRINT);
