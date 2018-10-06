<?php


namespace App;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Picqer\Barcode\BarcodeGenerator;
use Twig_Environment;

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
            "page-width","page-height","name_var_"
        ]);
        
        $resolver->setDefaults([
            "margin-left" => 0.0,
            "margin-top" => 0.0,
            "margin-right" => 0.0,
            "margin-bottom" => 0.0,
            "tmpDir" => null,
            "cacheDir" => null,
            "binary" => "wkhtmltopdf",
            "type_code" => BarcodeGenerator::TYPE_CODE_39,
        ]);
        
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("margin-left","float");
        $resolver->setAllowedTypes("margin-top","float");
        $resolver->setAllowedTypes("margin-right","float");
        $resolver->setAllowedTypes("margin-bottom","float");
        
        $this->options = $resolver->resolve($options);
    }

    public function generatePdf($filename,$barcode,$templateString,array $context = [],array $options = []) {
        //pdf
        $template =$this->twig->createTemplate($templateString);
        $view = $template->render($context);
        var_dump($view);
    }
    
    public function saveAs($htmlTwig) {
        
    }
}
