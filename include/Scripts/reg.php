<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}include/core.php";
require_once ROOT . 'include/Classes/Validator.class.php';

$errors = [];

$args = [
    'login'           => FILTER_SANITIZE_STRING,
    'password'        => FILTER_SANITIZE_STRING,
    'password-repeat' => FILTER_SANITIZE_STRING,
    'email'           => FILTER_VALIDATE_EMAIL,
];
$data = filter_var_array($_POST, $args);

$core = new Core();
if (!$core->db->connect()) {
    die(implode('\r\n', $core->getErrors()));
}

$query = <<< SQL
    SELECT `login`
    FROM `user`
SQL;

$logins = $core->db->qfa($query)[0];

$rules = [
    'login' => [
        'min' => [
            'value' => 3,
            'message' => 'Длина логина не может быть менее 3 символов',
        ],
        'unique' => [
            'value' => $logins,
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

if (count($results['errors']) > 0) {
    echo json_encode($results, JSON_PRETTY_PRINT);

    die();
}

$query = <<< SQL
    INSERT INTO `user` (`login`, `password`, `email`)
    VALUES (:login, :password, :email);
SQL;

$data = [
    ':login' => $data['login'],
    ':password' => sha1($data['password'] . SALT),
    ':email' => $data['email'],
];

if ($core->db->query($query, $data)) {
    $_SESSION['user'] = $data['login'];

    $response = [
        'status' => 'success',
        'relocate' => '/profile.php',
    ];
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo json_encode($core->getErrors(), JSON_PRETTY_PRINT);
}
