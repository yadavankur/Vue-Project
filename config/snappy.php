<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(
            'page-size' => 'A4',
            'orientation' => 'Landscape',
//            'footer-center' => '[page]/[toPage]',
//            'footer-font-size' => 8,
//            'margin-bottom' => 6,
        ),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
