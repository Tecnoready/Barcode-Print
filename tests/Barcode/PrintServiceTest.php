<?php

namespace Tests\Tecnoready\Barcode;

use PHPUnit\Framework\TestCase;
use Tecnoready\Barcode\PrintService;
use Twig_Environment;

/**
 * Pruebas unitarias del servicio de impresion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrintServiceTest extends TestCase {
    
    private $barcode = "CB0080K001";

    /**
     * Se verifica que se genera el codigo qr
     */
    public function testGenerateQR() {
        $printService = $this->getPrintService();
        $result = $printService->generateQR($this->barcode);
        
/*
<?xml version="1.0" standalone="no" ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="374.4" height="60" version="1.1" xmlns="http://www.w3.org/2000/svg">
	<desc>*CB0080K001*</desc>
	<g id="bars" fill="black" stroke="none">
		<rect x="0" y="0" width="1.95" height="60" />
		<rect x="6.825" y="0" width="1.95" height="60" />
		<rect x="10.725" y="0" width="4.875" height="60" />
		<rect x="17.55" y="0" width="4.875" height="60" />
		<rect x="24.375" y="0" width="1.95" height="60" />
		<rect x="28.275" y="0" width="4.875" height="60" />
		<rect x="35.1" y="0" width="4.875" height="60" />
		<rect x="41.925" y="0" width="1.95" height="60" />
		<rect x="48.75" y="0" width="1.95" height="60" />
		<rect x="52.65" y="0" width="1.95" height="60" />
		<rect x="56.55" y="0" width="1.95" height="60" />
		<rect x="60.45" y="0" width="4.875" height="60" />
		<rect x="67.275" y="0" width="1.95" height="60" />
		<rect x="74.1" y="0" width="1.95" height="60" />
		<rect x="78" y="0" width="4.875" height="60" />
		<rect x="84.825" y="0" width="1.95" height="60" />
		<rect x="88.725" y="0" width="1.95" height="60" />
		<rect x="95.55" y="0" width="4.875" height="60" />
		<rect x="102.375" y="0" width="4.875" height="60" />
		<rect x="109.2" y="0" width="1.95" height="60" />
		<rect x="113.1" y="0" width="1.95" height="60" />
		<rect x="117" y="0" width="1.95" height="60" />
		<rect x="123.825" y="0" width="4.875" height="60" />
		<rect x="130.65" y="0" width="4.875" height="60" />
		<rect x="137.475" y="0" width="1.95" height="60" />
		<rect x="141.375" y="0" width="4.875" height="60" />
		<rect x="148.2" y="0" width="1.95" height="60" />
		<rect x="155.025" y="0" width="1.95" height="60" />
		<rect x="158.925" y="0" width="4.875" height="60" />
		<rect x="165.75" y="0" width="1.95" height="60" />
		<rect x="169.65" y="0" width="1.95" height="60" />
		<rect x="173.55" y="0" width="1.95" height="60" />
		<rect x="180.375" y="0" width="4.875" height="60" />
		<rect x="187.2" y="0" width="4.875" height="60" />
		<rect x="194.025" y="0" width="1.95" height="60" />
		<rect x="197.925" y="0" width="4.875" height="60" />
		<rect x="204.75" y="0" width="1.95" height="60" />
		<rect x="208.65" y="0" width="1.95" height="60" />
		<rect x="212.55" y="0" width="1.95" height="60" />
		<rect x="219.375" y="0" width="4.875" height="60" />
		<rect x="226.2" y="0" width="1.95" height="60" />
		<rect x="230.1" y="0" width="1.95" height="60" />
		<rect x="236.925" y="0" width="4.875" height="60" />
		<rect x="243.75" y="0" width="4.875" height="60" />
		<rect x="250.575" y="0" width="1.95" height="60" />
		<rect x="254.475" y="0" width="1.95" height="60" />
		<rect x="258.375" y="0" width="1.95" height="60" />
		<rect x="265.2" y="0" width="4.875" height="60" />
		<rect x="272.025" y="0" width="4.875" height="60" />
		<rect x="278.85" y="0" width="1.95" height="60" />
		<rect x="282.75" y="0" width="4.875" height="60" />
		<rect x="289.575" y="0" width="1.95" height="60" />
		<rect x="296.4" y="0" width="1.95" height="60" />
		<rect x="300.3" y="0" width="1.95" height="60" />
		<rect x="304.2" y="0" width="4.875" height="60" />
		<rect x="311.025" y="0" width="1.95" height="60" />
		<rect x="317.85" y="0" width="1.95" height="60" />
		<rect x="321.75" y="0" width="4.875" height="60" />
		<rect x="328.575" y="0" width="4.875" height="60" />
		<rect x="335.4" y="0" width="1.95" height="60" />
	</g>
</svg>
*/
        //Verifica que se genere el pdf con el hash generado del texto
        $this->assertTrue("612307274fad29e638d31401e5d3248c" == md5($result));
    }
    
    /**
     * Prueba que se genere el template con el codigo qr
     */
    public function testRenderView() {
        $printService = $this->getPrintService();
        
        $templateString = <<<EOF
            demo
            {{img_codigo_barras | raw}}
EOF;
        $view = $printService->renderView($this->barcode, $templateString);
/*
        demo
<?xml version="1.0" standalone="no" ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="374.4" height="60" version="1.1" xmlns="http://www.w3.org/2000/svg">
	<desc>*CB0080K001*</desc>
	<g id="bars" fill="black" stroke="none">
		<rect x="0" y="0" width="1.95" height="60" />
		<rect x="6.825" y="0" width="1.95" height="60" />
		<rect x="10.725" y="0" width="4.875" height="60" />
		<rect x="17.55" y="0" width="4.875" height="60" />
		<rect x="24.375" y="0" width="1.95" height="60" />
		<rect x="28.275" y="0" width="4.875" height="60" />
		<rect x="35.1" y="0" width="4.875" height="60" />
		<rect x="41.925" y="0" width="1.95" height="60" />
		<rect x="48.75" y="0" width="1.95" height="60" />
		<rect x="52.65" y="0" width="1.95" height="60" />
		<rect x="56.55" y="0" width="1.95" height="60" />
		<rect x="60.45" y="0" width="4.875" height="60" />
		<rect x="67.275" y="0" width="1.95" height="60" />
		<rect x="74.1" y="0" width="1.95" height="60" />
		<rect x="78" y="0" width="4.875" height="60" />
		<rect x="84.825" y="0" width="1.95" height="60" />
		<rect x="88.725" y="0" width="1.95" height="60" />
		<rect x="95.55" y="0" width="4.875" height="60" />
		<rect x="102.375" y="0" width="4.875" height="60" />
		<rect x="109.2" y="0" width="1.95" height="60" />
		<rect x="113.1" y="0" width="1.95" height="60" />
		<rect x="117" y="0" width="1.95" height="60" />
		<rect x="123.825" y="0" width="4.875" height="60" />
		<rect x="130.65" y="0" width="4.875" height="60" />
		<rect x="137.475" y="0" width="1.95" height="60" />
		<rect x="141.375" y="0" width="4.875" height="60" />
		<rect x="148.2" y="0" width="1.95" height="60" />
		<rect x="155.025" y="0" width="1.95" height="60" />
		<rect x="158.925" y="0" width="4.875" height="60" />
		<rect x="165.75" y="0" width="1.95" height="60" />
		<rect x="169.65" y="0" width="1.95" height="60" />
		<rect x="173.55" y="0" width="1.95" height="60" />
		<rect x="180.375" y="0" width="4.875" height="60" />
		<rect x="187.2" y="0" width="4.875" height="60" />
		<rect x="194.025" y="0" width="1.95" height="60" />
		<rect x="197.925" y="0" width="4.875" height="60" />
		<rect x="204.75" y="0" width="1.95" height="60" />
		<rect x="208.65" y="0" width="1.95" height="60" />
		<rect x="212.55" y="0" width="1.95" height="60" />
		<rect x="219.375" y="0" width="4.875" height="60" />
		<rect x="226.2" y="0" width="1.95" height="60" />
		<rect x="230.1" y="0" width="1.95" height="60" />
		<rect x="236.925" y="0" width="4.875" height="60" />
		<rect x="243.75" y="0" width="4.875" height="60" />
		<rect x="250.575" y="0" width="1.95" height="60" />
		<rect x="254.475" y="0" width="1.95" height="60" />
		<rect x="258.375" y="0" width="1.95" height="60" />
		<rect x="265.2" y="0" width="4.875" height="60" />
		<rect x="272.025" y="0" width="4.875" height="60" />
		<rect x="278.85" y="0" width="1.95" height="60" />
		<rect x="282.75" y="0" width="4.875" height="60" />
		<rect x="289.575" y="0" width="1.95" height="60" />
		<rect x="296.4" y="0" width="1.95" height="60" />
		<rect x="300.3" y="0" width="1.95" height="60" />
		<rect x="304.2" y="0" width="4.875" height="60" />
		<rect x="311.025" y="0" width="1.95" height="60" />
		<rect x="317.85" y="0" width="1.95" height="60" />
		<rect x="321.75" y="0" width="4.875" height="60" />
		<rect x="328.575" y="0" width="4.875" height="60" />
		<rect x="335.4" y="0" width="1.95" height="60" />
	</g>
</svg>
*/
        $this->assertTrue("ec603e3013dc906413b83d716e83a070" === md5($view));
    }
    
    /**
     * Prueba que se genera el pdf con data real
     */
    public function testGeneratePdf() {
        $printService = $this->getPrintService();
        
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
    {{img_codigo_barras | raw}}<br /><strong>{{codigo_barras}}</strong><br /><strong>{{texto_inconforme}}</strong>
</p>
EOF;
        $templateString = <<<EOF
<table border="0" cellspacing="0">
<tbody>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px; height: 0px;" colspan="2"><strong>{{extrusion.numero}}</strong></td>
</tr>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px; height: 0px;" width="50%">Ancho: {{producto.ancho}}mm</td>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px; height: 0px;" width="50%">Peso: {{rollo.peso}}kg</td>
</tr>
<tr>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px;">Espesor: {{producto.espesor}}mic</td>
<td style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px;">Largo: {{extrusion.largo_rollo}}m</td>
</tr>
</tbody>
</table>
<p style="text-align: center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 8px;">{{img_codigo_barras | raw}}<br /><strong>{{codigo_barras}}</strong> <strong>{{texto_inconforme}}</strong></p>
EOF;
        
        $filename = $this->getFileNameDemo();
        
        $barcode = $this->barcode;
        
        $extrusion = [
            "numero" => "EC00252",
            "ancho" => 20,
            "espesor" => 20,
            "largo_rollo" => "4789.4",
        ];
        $producto = [
            "ancho" => "ancho",
            "espesor" => "espesor",
        ];
        $context = [
            "extrusion" => $extrusion,
            "codigo_barras" =>  $barcode,
            "texto_inconforme" =>  "Texto en variable conforme.",
            "producto" =>  $producto,
            "rollo" => [
                "peso" => "peso"
            ]
        ];
        
        $printService->generatePdf($filename, $barcode, $templateString,$context,[
            "copies" => 1,
        ]);
        $this->assertFileExists($filename);
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
    
    /**
     * @return PrintService
     */
    private function getPrintService() {
        $twig = $this->getTwig();
        $options = [
            "page-width" => 49.0,
            "page-height" => 27.5,
            "var-barcode" => "img_codigo_barras",
            "barcode-height" => 30,
            "barcode-width-factor" => 0.96,
        ];
        return new PrintService($twig, $options);
    }

    private function getFileNameDemo() {
        $filename = __DIR__."/../demo.pdf";
//        @unlink($filename);
        return $filename;
    }
    
    protected function tearDown() {
        $this->getFileNameDemo();
    }

}
