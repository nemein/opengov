<!DOCTYPE HTML PUBLIC"-// W3C//DTD HTML 4.01//EN"" http://www.w3.org/TR/html4/strict.dtd">
<html lang="fi">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?php echo $_MIDCOM->i18n->get_string('welcome', 'fi.opengov.datacatalog'); ?></title>
        <?php
            if (! $_MIDCOM->auth->user)
            {
                $_MIDCOM->enable_jquery();
            }
            $_MIDCOM->print_head_elements();
        ?> 
        <link rel="stylesheet" href="/style/css/reset-fonts-grids.css" type="text/css">
        <link href="/style/css/screen5.css" rel="stylesheet" type="text/css" media="screen">
        <link href="/style/css/print2.css" rel="stylesheet" type="text/css" media="print">
        <!--[if IE 7]><link href="/syle/css/ie7.css" rel="stylesheet" type="text/css"><![endif]-->
        <!--[if lte IE 7]><link href="/style/css/ie6-7.css" rel="stylesheet" type="text/css"><![endif]-->
        <link rel="schema.DC" href="http://purl.org/DC/elements/1.0/">
    </head>
    <body>
        <div id="doc4" class="yui-t7">
            <div id="hd">
                <(page-top-header)>
            </div>
            <div id="bd">
                <div class="yui-g">
                    <(page-navigation)>
                </div>
                <div class="yui-gc">
                    <div class="yui-u first">
                        <div id="content">
                            <(content)>
                        </div>
                    </div>
                    <div class="yui-u sidebar">
                        <(page-sidebar)>
                    </div>
                </div>
                <div class="yui-g robots-nocontent">                    
                </div>
            </div>
            <div id="ft" class="robots-nocontent footer" role="contentinfo">
                <(page-footer)>
            </div>
        </div>
        <?php
        $_MIDCOM->toolbars->show();
        $_MIDCOM->uimessages->show();
        ?>
        <!-- Place your user stat scripts here -->
    </body>
</html>
