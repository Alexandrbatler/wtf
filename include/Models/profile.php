<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/include/Classes/Db.class.php";

$db = new Db();
$db->connect();

$login = $_SESSION['user'];
$count = 0;
$bestScore = 0;
$email = '';

$data = [
    ':login' => $login,
];

$query = <<< SQL
    SELECT `email`
    FROM `user`
    WHERE `login` = :login;
SQL;

if ($result = $db->qfa($query, $data)) {
    $email = $result[0][0];
}

$query = <<< SQL
    SELECT DISTINCT COUNT(*)
    FROM `game`
        INNER JOIN `user` ON `game`.`user_id` = `user`.`id`
    WHERE `user`.`login` = :login
SQL;

if ($result = $db->qfa($query, $data)) {
    $count = (int)$result[0][0];
}

$labelCol = 'col-xs-2';
$inputCol = 'col-xs-10';
$offsetCol = 'col-xs-offset-2';
echo <<< HTML
    <div class="profile__container">
        <div class="profile__login">{$login}</div>
        <div class="profile__count">{$count} сыгранных игр</div>
        <div class="profile__best">{$bestScore} угаданных фильмов подряд</div>
        <div class="profile__button"><a href="#" class="profile__button-start btn btn-success">Начать новую игру</a></div>
        
        <hr>
        
        <div class="profile__settings">
            <div class="row">
                <div class="profile__settings--current col-xs-6">
                    <h3 class="profile__settings-heading">Текущие настройки</h3>
                    <div class="row">
                        <div class="{$labelCol}"><strong>Email</strong></div>
                        <div class="{$inputCol}">{$email}</div>
                    </div>
                </div>
                
                <div class="profile__settings--change col-xs-6">
                    <h3 class="profile__settings-heading">Изменить настройки</h3>
                    
                    <form action="#" method="post" class="profile__form profile__form--change form-horizontal">
                        <div class="form-group">
                            <label for="email" class="profile__form-label {$labelCol} control-label">Email</label>
                            <div class="{$inputCol}">
                                <input type="email" name="email" class="form-control input input__email" placeholder="Email *">
                                <div class="text-danger error error-email hide"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="profile__form-label {$labelCol} control-label">Пароль</label>
                            <div class="{$inputCol}">
                                <input type="password" name="password" class="form-control input input__password" placeholder="Пароль *">
                                <div class="text-danger error error-password hide"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="{$inputCol} {$offsetCol}">
                                <input type="password" name="password-repeat" class="form-control" placeholder="Повторите ваш пароль *">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="{$inputCol} {$offsetCol}">
                                <button type="submit" class="btn btn-warning btn-registry">Изменить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
HTML;
