<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use App\PrintService;
use Twig_Environment;

/**
 * Pruebas unitarias del servicio de impresion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrintServiceTest extends TestCase {

    public function testGeneratePdf() {
        $twig = $this->getTwig();
        $options = [
            "page-width" => 27.5,
            "page-height" => 49,
            "var-barcode" => "img_codigo_barras",
            "barcode-height" => 60,
            "barcode-width-factor" => 1.95,
        ];
        $printService = new PrintService($twig, $options);
        
        $templateString = <<<EOF
<table style="width: 100%; margin-left: auto; margin-right: auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 16px;">
    <tbody>
    <tr>
        <td style="padding: 0 0 0px;" colspan="3">
            <strong>{{extrusion.numero}}</strong><br />Ancho: <strong>{{extrusion.ancho}}</strong><br />Espesor: <strong>{{extrusion.espesor}}</strong><br />Largo: <strong>{{extrusion.largo_rollo}}</strong>
        </td>
    </tr>
    </tbody>
</table>
<p style="text-align: center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px;">
    {{img_codigo_barras}}<br /><strong>{{codigo_barras}}</strong><br /><strong>{{texto_inconforme}}</strong>
</p>
EOF;
        $filename = "demo.pdf";
        $barcode = "CB0080K001";
        
        $extrusion = [
            "numero" => 52,
            "ancho" => 20,
            "espesor" => 20,
            "largo_rollo" => "100",
        ];
        $context = [
            "extrusion" => $extrusion,
            "codigo_barras" =>  $barcode,
            "texto_inconforme" =>  "Texto en variable conforme.",
        ];
        
        $printService->generatePdf($filename, $barcode, $templateString,$context);
    }

    /**
     * @return Twig_Environment
     */
    private function getTwig() {
        $loader = new \Twig_Loader_Array();
        $twig = new Twig_Environment($loader,[
            "strict_variables" => true,
        ]);
        return $twig;
    }

}
