<?php


namespace App;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Servicio para generar documento para imprimir
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrintService 
{
    private $options;
    
    function __construct($options) {
        
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "page-width","page-height"
        ]);
        
        $resolver->setDefaults([
            "margin-left" => 0,
            "margin-top" => 0,
            "margin-right" => 0,
            "margin-bottom" => 0,
        ]);
        
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("margin-left","float");
        $resolver->setAllowedTypes("margin-top","float");
        $resolver->setAllowedTypes("margin-right","float");
        $resolver->setAllowedTypes("margin-bottom","float");
        
        $this->options = $resolver->resolve($options);
    }

    public function functionName($contenidoPlantilla,$parametros,$code) {
        
    }
    
    public function saveAs($htmlTwig) {
        
    }
}
