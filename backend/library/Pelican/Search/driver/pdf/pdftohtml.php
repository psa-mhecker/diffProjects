<?php
    /**
     */
    include_once 'config.php';

    include Pelican::$config['LIB_ROOT'].Pelican::$config['LIB_SEARCH']."/pdf/Pdftohtml.php";

    $options[] = "-q";
    $options[] = "-i";
    $options[] = "-noframes";

    $conf["options"] = implode(" ", $options);

    $pdf = $_REQUEST["pdf"];
    $pdf = Pelican_Text::rawurldecode($pdf);
    $pdftohtml = new Pdftohtml($conf);
    $return = $pdftohtml->convert($pdf);
    echo($return);
