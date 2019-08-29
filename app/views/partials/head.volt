<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $t->_('description'); ?>">
    <meta name="keywords" content="<?php echo $t->_('keywords'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:site_name" content="<?php echo $t->_('app_name'); ?>"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('img/favicon.ico') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{ get_title() }}
    {{ stylesheet_link('css/lite.css') }}

    <style type="text/css">
        .brand, .brand .betslip {
            background-color: #FFF !important;
        }

        .betslip a {
            color: #101721 !important;
        }

        .betslip .betslip--count {
            color: black !important;
            background-color: #fbd702 !important;
            margin-left: 5px;
        }
    </style>
</head>