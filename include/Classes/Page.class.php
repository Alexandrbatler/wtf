<?php

class Page
{
    private $css = [];
    private $scripts = [];

    public function __construct()
    {
        $this->addCSS('/css/styles.css');

        $this->addScript('/js/jquery-3.2.1.min.js');
        $this->addScript('/js/app.js');
    }

    public function getHeader()
    {
        $action = isset($_SESSION['user'])
            ? <<< HTML
                <div class="col-xs-6">
                    <a href="#" class="header__link header__logout">Выйти</a>
                </div>
HTML
            : <<< HTML
                <div class="col-xs-6">
                    <a href="#" class="header__link header__action" data-form="login">Войти</a>
                </div>
HTML;

        $html = <<< HTML
            <header class="header__container">
                <div class="row container">
                    <div class="col-xs-6">
                        <h2 class="header__logo">What The Film?!</h2>
                    </div>
                    {$action}
                </div>
            </header>
HTML;

        return $html;
    }

    public function getContent()
    {
        $html = '';

        $model = stristr($_SERVER['PHP_SELF'], '.php', true);
        $model = mb_substr($model, 1);

        $pathToModel = ROOT . "include/Models/{$model}.php";
        if (file_exists($pathToModel)) {
            ob_start();

            require_once $pathToModel;

            $html = ob_get_clean();
        }

        return $html;
    }

    public function getFooter()
    {
        $date = date('Y');

        $html = <<< HTML
            <footer class="footer__container">
                <div class="row container">
                    <div class="col-xs-10">
                        <a href="#" class="footer__rating">Рейтинг игроков</a>
                    </div>
                    <div class="col-xs-2 footer__copyright">
                        "WTF?!" © {$date}
                    </div>
                </div>
            </footer>
HTML;

        return $html;
    }


    public function addScript($path) {
        $this->scripts[] = $path;
    }

    public function getScripts() {
        $html = '';

        foreach ($this->scripts as $script) {
            $html .= "<script src='{$script}'></script>";
        }

        return $html;
    }

    public function addCSS($path) {
        $this->css[] = $path;
    }

    public function getCSS() {
        $html = '';

        foreach ($this->css as $css) {
            $html .= "<link rel='stylesheet' href='{$css}'/>";
        }

        return $html;
    }
}