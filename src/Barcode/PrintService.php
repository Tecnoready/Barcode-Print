<?php

namespace Tecnoready\Barcode;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Picqer\Barcode\BarcodeGenerator;
use Twig_Environment;
use mikehaertl\wkhtmlto\Pdf;
use Tecnoready\Barcode\Generator\BarcodeGeneratorSVG;
use Tecnoready\Barcode\Exception\CacheException;
use Tecnoready\Barcode\Exception\GeneratePdfException;

/**
 * Servicio para generar documento para imprimir
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrintService 
{
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    /**
     * Instancia de twig para renderizar las plantillas
     * @var Twig_Environment 
     */
    private $twig;
    
    function __construct(Twig_Environment $twig,$options) {
        $this->twig = $twig;
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "page-width",//Ancho del pdf en mm (milimetros)
            "page-height",//Alto del pdf en mm (milimetros)
            "var-barcode",//Nombre de la variable dentro de la plantilla twig donde se generara el qr
            "barcode-height",//Alto del codigo de barras
            "barcode-width-factor",//Grosor de la barra del codigo
        ]);
        $tmpDir = sys_get_temp_dir();
        
        $resolver->setDefaults([
            "barcode-color" => "black",//Color del codigo de barras
            
            "tmpDir" => $tmpDir,//Carpeta temporal
            "binary" => "wkhtmltopdf",//Binario
            "type_code" => BarcodeGenerator::TYPE_CODE_39,//Tipo de codigo por defecto
        ]);
        
        
        $resolver->setAllowedTypes("barcode-width-factor","float");
        
        $resolver->setAllowedTypes("var-barcode","string");//Nombre donde se rendizara el codigo de barras
        
        $this->options = $resolver->resolve($options);
        
        if (!is_writable($this->options["tmpDir"])) {
            throw new CacheException(sprintf("El directorio temporal '%s' no se puede escribir.",$this->options["tmpDir"]));
        }
    }

    /**
     * Genera un pdf con un qr a partir de una plantilla twig
     * @param type $filename Ruta absoluta el archivo pdf a generar
     * @param type $code Codigo de barra a generar
     * @param type $templateString Plantilla twig en string
     * @param array $context Parametros de plantilla twig
     * @param array $options Sobrescribir opciones de wkhtmltopdf, sino se pasa se usan los valores por defecto
     * @return boolean
     * @throws Exception
     */
    public function generatePdf($filename,$code,$templateString,array $context = [],array $options = []) {
        $view = $this->renderView($code,$templateString, $context);
        
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            "page-width","page-height"
        ]);
        
        //Parametros para generar el pdf
        $resolver->setDefaults([
            "margin-left" => 0.0,//Margen izquierdo
            "margin-top" => 0.0,//Margen superior
            "margin-right" => 0.0,//Margen derecho
            "margin-bottom" => 0.0,//Margen inferior
            'encoding' => 'UTF-8',  // option with argument
            'no-outline',           // option without argument
            "page-width" => $this->options["page-width"],
            "page-height" => $this->options["page-height"],
        ]);
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("margin-left","float");
        $resolver->setAllowedTypes("margin-top","float");
        $resolver->setAllowedTypes("margin-right","float");
        $resolver->setAllowedTypes("margin-bottom","float");
        
        $options = $resolver->resolve($options);
        
        $pdf = new Pdf();
        $pdf->tmpDir = $this->options["tmpDir"];
        $pdf->binary = $this->options["binary"];
        $pdf->setOptions($options);
        $pdf->addPage('<html>'.$view.'</html>');
        
        if (!$pdf->saveAs($filename)) {
            $error = $pdf->getError();
            //sh: wkhtmltopdf: command not found (Error que da cuando no encuentra el binario de wkhtmltopdf)
            throw new GeneratePdfException(sprintf("Ocurrio un error generando el pdf: '%s'",$error));
        }
        return true;
    }

    /**
     * Genera el codigo QR en html con SVG
     * @param type $code string del codigo qr
     * @return string
     */
    public function generateQR($code) {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcode($code,$this->options["type_code"],$this->options["barcode-width-factor"],$this->options["barcode-height"],$this->options["barcode-color"]);
    }
    
    /**
     * Rendezia la vista
     * @param type $code
     * @param type $templateString
     * @param array $context
     * @return type
     */
    public function renderView($code,$templateString,array $context = []) {
        $context[$this->options["var-barcode"]] = $this->generateQR($code);
        
        $template =$this->twig->createTemplate($templateString);
        return $template->render($context);
    }
}
