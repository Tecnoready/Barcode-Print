<?php

namespace App;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Picqer\Barcode\BarcodeGenerator;
use Twig_Environment;
use mikehaertl\wkhtmlto\Pdf;

/**
 * Servicio para generar documento para imprimir
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrintService 
{
    private $options;
    private $twig;
    
    function __construct(Twig_Environment $twig,$options) {
        $this->twig = $twig;
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "page-width","page-height","var-barcode","barcode-height","barcode-width-factor"
        ]);
        if (@is_writable($tmpDir = sys_get_temp_dir())) {
            
        }
        
        $resolver->setDefaults([
            "barcode-color" => "black",
            
            "tmpDir" => $tmpDir,
            "cacheDir" => $tmpDir,
            "binary" => "wkhtmltopdf",
            "type_code" => BarcodeGenerator::TYPE_CODE_39,
        ]);
        
        
        $resolver->setAllowedTypes("barcode-width","float");
        
        $resolver->setAllowedTypes("var-barcode","string");//Nombre donde se rendizara el codigo de barras
        
        $this->options = $resolver->resolve($options);
    }

    public function generatePdf($filename,$code,$templateString,array $context = [],array $options = []) {
        //pdf
        $context[$this->options["var-barcode"]] = $this->generateQR($code);
        $view = $this->renderView($templateString, $context);
        
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            "page-width","page-height"
        ]);
        $resolver->setDefaults([
            "margin-left" => 0.0,
            "margin-top" => 0.0,
            "margin-right" => 0.0,
            "margin-bottom" => 0.0,
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
        $pdf->setOptions($options);
        $pdf->addPage('<html>'.$view.'</html>');
        
        echo($view);
        if (!$pdf->saveAs(__DIR__.'/page.pdf')) {
            $error = $pdf->getError();
            var_dump($error);
            die;
        //    sh: wkhtmltopdf: command not found
            // ... handle error here
        }
    }
    


    public function generateQR($code) {
        $generator = new \App\Generator\BarcodeGeneratorSVG();
        return $generator->getBarcode($code,$this->options["type_code"],$this->options["barcode-width-factor"],$this->options["barcode-height"],$this->options["barcode-color"]);
    }
    
    private function renderView($templateString,array $context = []) {
        $template =$this->twig->createTemplate($templateString);
        return $template->render($context);
    }

}
