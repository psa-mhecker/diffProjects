<?php
// CAPTCHA
Pelican::$config['FORMBUILDER']['CAPTCHA']['PUBLIC_KEY'] = '6LcqM_QSAAAAAKn8ODVE3r81CckiSj3K4UzG_5iG';
Pelican::$config['FORMBUILDER']['CAPTCHA']['PRIVATE_KEY'] = '6LcqM_QSAAAAAOyhuNJR2kLnufac3X2aLYBpsM6v';

// MAIL
Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['ID'] = array(
    'FORMBUILDER_TYPE_CLIENT',
    'FORMBUILDER_TYPE_ADMIN',
);

// 'FORMBUILDER_TYPE_PROVIDER'
Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['DEFAULT'] = array(
    'FORMBUILDER_TYPE_CLIENT' => array(
        'FORMBUILDER_MAIL_DEST' => '%MAIL%',
    ),
);

Pelican::$config['FORMBUILDER']['MAIL']['ATTACHMENT_NUMBER'] = 3;
