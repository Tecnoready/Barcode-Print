<?php

namespace App\Generator;

use Picqer\Barcode\BarcodeGeneratorHTML as Base;

/**
 * Generador de codigos en html
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class BarcodeGeneratorHTML extends Base 
{
    public function getBarcode($code, $type, $widthFactor = 2, $totalHeight = 30, $color = 'black') {
        if($type !== self::TYPE_CODE_39){
            return parent::getBarcode($code, $type, $widthFactor, $totalHeight, $color);
        }
        $barcodeData = $this->getBarcodeData($code, $type);

        $html = '<div style="font-size:0;position:relative;width:' . ($barcodeData['maxWidth'] * $widthFactor) . 'px;height:' . ($totalHeight) . 'px;">' . "\n";

        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            if($bar['width'] === "3"){
                $bar['width'] = "2";
            }
            if($bar['width'] === "1"){
                $bar['width'] = "1";
            }
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);

            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $html .= '<div style="background-color:' . $color . ';width:' . $barWidth . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $positionHorizontal . 'px;top:' . $positionVertical . 'px;">&nbsp;</div>' . "\n";
            }

            $positionHorizontal += $barWidth;
        }
        $html .= '</div>' . "\n";

        return $html;
    }
}
