<?php
if (PHP_SAPI == 'cli') {
    // If the index.php is called using console, we would try to host
    // the built in PHP Server
    if (version_compare(phpversion(), '5.4.0', '>=') === true) {
        //exec('php -S -t ' . __DIR__ . '/');
        $cmd = "php -S 0.0.0.0:4000 -t " . __DIR__;
        $descriptors = array(
            0 => array("pipe", "r"),
            1 => STDOUT,
            2 => STDERR,
        );
        $process = proc_open($cmd, $descriptors, $pipes);
        if ($process === false) {
            fprintf(STDERR, "Unable to launch PHP's built-in web server.\n");
            exit(2);
        }
        fclose($pipes[0]);
        $exit = proc_close($process);
        exit($exit);
    } else {
        echo "You must be running PHP version less than 5.4. You would have to manually host the website on your local web server.\n";
        exit(2);
    }
}
/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__ . '/../vendor/autoload.php';


//$generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
$generator = new \Picqer\Barcode\BarcodeGeneratorSVG();//Ultimos pdf con svg!
//$generator = new \App\Generator\BarcodeGeneratorHTML();
$code = "EX00014K0005";
$code = "EX0014K005";
$code = "C011921022";
//$code = "C";
$type = $generator::TYPE_CODE_39;
$width = 2;
$totalHeight = 66;
$html = <<<EOF
<table border="0">
<tbody>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px; height: 5px;" colspan="2"><strong>EX00024</strong></td>
</tr>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px; height: 5px;" width="50%">Ancho: 1430 mm</td>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px; height: 5px;" width="50%">Peso: 204.15 kg</td>
</tr>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px;">Espeso: 30 mic</td>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px;">Largo: 5157.08 M</td>
</tr>
</tbody>
</table>
EOF;
$html .= $generator->getBarcode($code,$type,$width,$totalHeight);
$html .= <<<EOF
<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 10px;">$code</div>
        width: 1.9, separator: 1.3
EOF;


use mikehaertl\wkhtmlto\Pdf;

// You can pass a filename, a HTML string, an URL or an options array to the constructor
$pdf = new Pdf();
$pdf->addPage('<html>'.$html.'</html>');

$options = array(
    'no-outline',           // option without argument
    'encoding' => 'UTF-8',  // option with argument
    'margin-left' => 0,
    'margin-top' => 0,
    'margin-right' => 0,
    'margin-bottom' => 0,
    'page-width' => 49.5,
    'page-height' => 27.5,
);
//140 × 78
$pdf->setOptions($options);

// On some systems you may have to set the path to the wkhtmltopdf executable
// $pdf->binary = 'C:\...';

if (!$pdf->saveAs(__DIR__.'/page.pdf')) {
    $error = $pdf->getError();
    var_dump($error);
    die;
//    sh: wkhtmltopdf: command not found
    // ... handle error here
}
$pdf->send();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        Demo de pdf
        <?php
        echo $html;
        ?>
    </body>
</html>
