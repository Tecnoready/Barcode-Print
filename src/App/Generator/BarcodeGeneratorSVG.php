<?php

namespace App\Generator;

use Picqer\Barcode\BarcodeGeneratorSVG as Base;

/**
 * Generador de qr con svg
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class BarcodeGeneratorSVG extends Base
{
    /**
     * Return a SVG string representation of barcode.
     *
     * @param $code (string) code to print
     * @param $type (const) type of barcode
     * @param $widthFactor (int) Minimum width of a single bar in user units.
     * @param $totalHeight (int) Height of barcode in user units.
     * @param $color (string) Foreground color (in SVG format) for bar elements (background is transparent).
     * @return string SVG code.
     * @public
     */
    public function getBarcode($code, $type, $widthFactor = 2, $totalHeight = 30, $color = 'black')
    {
        $barcodeData = $this->getBarcodeData($code, $type);

        // replace table for special characters
        $repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');

        $svg = '<?xml version="1.0" standalone="no" ?>' . "\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n";
        $svg .= '<svg width="' . round(($barcodeData['maxWidth'] * $widthFactor),
                3) . '" height="' . $totalHeight . '" version="1.1" xmlns="http://www.w3.org/2000/svg">' . "\n";
        $svg .= "\t" . '<desc>' . strtr($barcodeData['code'], $repstr) . '</desc>' . "\n";
        $svg .= "\t" . '<g id="bars" fill="' . $color . '" stroke="none">' . "\n";
        // print bars
        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            if($bar['width'] === "3"){
                $bar['width'] = "1.9";
            }
            if($bar['width'] === "1"){
                $bar['width'] = "1.3";
            }
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $svg .= "\t\t" . '<rect x="' . $positionHorizontal . '" y="' . $positionVertical . '" width="' . $barWidth . '" height="' . $barHeight . '" />' . "\n";
            }
            $positionHorizontal += $barWidth;
        }
        $svg .= "\t" . '</g>' . "\n";
        $svg .= '</svg>' . "\n";

        return $svg;
    }
}