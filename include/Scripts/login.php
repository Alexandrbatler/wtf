<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}include/core.php";
require_once ROOT . 'include/Classes/Validator.class.php';

$loginErrText = 'Возможно, неверно введен логин';
$passErrText = 'Возможно, неверно введен пароль';
$errors = [];

$args = [
    'login'    => FILTER_SANITIZE_STRING,
    'password' => FILTER_SANITIZE_STRING,
];
$data = filter_var_array($_POST, $args);

$core = new Core();
if (!$core->db->connect()) {
    die(implode('\r\n', $core->getErrors()));
}

$query = <<< SQL
    SELECT `password`
    FROM `user`
    WHERE `login` = :login
SQL;

$params = [
    ':login' => $data['login'],
];

$pass = $core->db->qfa($query, $params);

if (count($pass) === 0) {
    $response = [
        'errors' => [
            'login'    => [$loginErrText],
            'password' => [$passErrText],
        ],
    ];

    die(json_encode($response, JSON_PRETTY_PRINT));
}

$params = [
    'password' => sha1($data['password'] . SALT),
];
$rules = [
    'password' => [
        'equal' => [
            'value'   => $pass[0][0],
            'message' => $passErrText,
        ],
    ],
];

$validator = new Validator($params, $rules);
$results = $validator->validate();

if (count($results['errors']) > 0) {
    $results['errors']['login'] = [$loginErrText];

    die(json_encode($results, JSON_PRETTY_PRINT));
}

$_SESSION['user'] = $data['login'];
$response = [
    'status'   => 'success',
    'relocate' => '/profile.php',
];

die(json_encode($response, JSON_PRETTY_PRINT));
