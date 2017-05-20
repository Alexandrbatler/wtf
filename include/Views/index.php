<?php

echo <<< HTML
    <div class="col-xs-8 main__description">
        <header class="main__title">
            <h2 class="main__heading">WTF?!</h2>
            <p class="main__subheading">What the Film?!</p>
        </header>
        <hr class="main__hr">
        <section class="main__description">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, alias esse fugit impedit in
            libero magnam minus nulla odit perferendis provident tempora voluptate. Aliquid doloremque, et eum
            excepturi in quasi quisquam soluta vel. A dicta doloremque dolores excepturi nisi perspiciatis
            placeat. Aut dicta dignissimos doloribus dolorum error facilis hic labore, libero magni molestias
            natus officia placeat reiciendis! Aperiam at beatae commodi cupiditate deserunt dignissimos dolores
            doloribus eos error et exercitationem expedita explicabo illum impedit iste laboriosam magnam magni
            minima modi molestiae natus non nostrum praesentium, quae quam quis quod recusandae rerum sed sequi
            suscipit tempore temporibus ullam. Amet ea eius labore laborum minus neque quas, rerum vel
            voluptates. A ad adipisci autem cum cumque dicta doloremque eos error esse eum facilis, illo
            incidunt ipsam iure magnam molestiae molestias nostrum numquam odio omnis quaerat quam rem
            repudiandae sit temporibus ullam unde! Aperiam, aut sequi! Ad adipisci inventore odio quas quasi
            vitae.
        </section>
        <div class="main__start">
            <a href="#" class="btn btn-warning main__start-btn">Start the Game!</a>
        </div>
    </div>

    <div class="col-xs-3 col-xs-offset-1 main__reg">
        <form action="/include/Scripts/reg.php" method="post" class="main__reg-form">
            <h3>Registry</h3>
            <hr class="main__hr">
            <div class="form-group">
                <input type="text" name="login" class="form-control input input__login" placeholder="Логин *">
                <div class="text-danger error error-login hide"></div>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control input input__email" placeholder="Email *">
                <div class="text-danger error error-email hide"></div>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control input input__password" placeholder="Пароль *">
                <div class="text-danger error error-password hide"></div>
            </div>
            <div class="form-group">
                <input type="password" name="password-repeat" class="form-control" placeholder="Повторите ваш пароль *">
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-registry">Регистрация</button>
        </form>
    </div>
HTML;
