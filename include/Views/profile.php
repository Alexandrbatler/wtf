<?php

echo <<< HTML
    <div class="profile__container">
        <div class="profile__login">{$login}</div>
        
        <div class="profile__count">Количество игр: {$count}</div>
        
        <div class="profile__best">Лучший счет: {$bestScore} угаданных фильмов подряд</div>
        
        <div class="profile__settings">
            <div class="profile__settings--current">
                <h3>Текущие настройки</h3>
            </div>
            
            <div class="profile__settings--change">
                <h3>Изменить настройки</h3>
            </div>
        </div>
    </div>
HTML;
