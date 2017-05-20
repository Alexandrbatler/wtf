<?php

class Page
{
    private $css = [];
    private $scripts = [];

    public function __construct()
    {
        $this->addCSS('css/styles.css');

        $this->addScript('/js/jquery-3.2.1.min.js');
        $this->addScript('/js/app.js');
    }

    public function getHeader()
    {
        $html = <<< HTML
            <header class="header__container">
                <div class="row container">
                    <div class="col-xs-4">
                        <h2 class="header__logo">What The Film?!</h2>
                    </div>
                    <div class="col-xs-8">
                        <a href="#" class="header__auth">Login</a>
                    </div>
                </div>
            </header>
HTML;

        return $html;
    }

    public function getContent()
    {
        $html = '';

        $view = stristr($_SERVER['PHP_SELF'], '.php', true);
        $view = mb_substr($view, 1);

        $pathToView = ROOT . "include/Views/{$view}.php";
        if (file_exists($pathToView)) {
            ob_start();

            require_once $pathToView;

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
                        <a href="#" class="footer__rating">Rating</a>
                    </div>
                    <div class="col-xs-2 footer__copyright">
                        wtf.local © {$date}
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