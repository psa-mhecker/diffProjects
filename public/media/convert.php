<?php
        $tmpdir = './tmp';
        $wk = './wkhtmltopdf-amd64';
        if (!is_executable($wk)) die('Unable to find the pdf converter');
        if (!is_dir($tmpdir)) die('tmpdir does not exists');
        // Gestiond des paramtres
        if (!isset($_POST['url']) && !isset($_GET['url'])) {
        	die('no parameters found');
        }
        if (!empty($_GET['url'])) {
        	$url = escapeshellcmd($_GET['url']);
        } else if (!empty($_POST['url'])) {
        	$url = escapeshellcmd($_POST['url']);
        }
        // Pour tests
		// $_POST['url'] = 'http://www.education.gouv.fr';
        
        // $url = escapeshellcmd($_POST['url']);
        $filename = preg_replace('@^(https?)://(.*)$@i', '\1_\2', $url);
        $filename = str_replace('/', '-', $filename);
        $filename = preg_replace('@[^a-zA-Z0-9_\-\.]@i', '', $filename);
        $filename = "$filename.pdf";

        $options = '';
        if (isset($_POST['options'])) {
            $options = urldecode($_POST['options']);
        } else if (isset($_GET['options'])) {
			$options = urldecode($_GET['options']);
        }

        $tmp = tempnam($tmpdir, "convertpdf_");
        if ($tmp === FALSE || !is_file($tmp)) die('unable to create pdf temporary file');

        $ret = exec("$wk $options $url $tmp 2>&1");
        if (preg_match('/done/i', $ret) < 1) die('Unable to convert');
        clearstatcache();

        if (!is_file($tmp)) die('unable to find the generated pdf');

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$filename\";");
        header("Content-Transfer-Encoding:  binary");
        header("Content-Length: ".filesize($tmp));
        readfile($tmp);
        @unlink($tmp);
        exit;
?>
