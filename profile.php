<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}include/core.php";

$core = new Core();

$css = $core->page->getCSS();
$header = $core->page->getHeader();
$content = $core->page->getContent();
$footer = $core->page->getFooter();

print <<< HTML
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
    
        <title>WTF? - What the Film?</title>
    
        {$css}
    </head>
    <body>
    
    <div class="site__container">
    
        {$header}
    
        <section class="main__container">
            <div class="main__bg"></div>
            <div class="row container">
            
                {$content}
                
            </div>
        </section>
    
        {$footer}
        
    </div>
    
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/app.js"></script>
    </body>
    </html>
HTML;
