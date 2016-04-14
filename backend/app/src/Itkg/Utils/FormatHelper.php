<?php

namespace Itkg\Utils;

class FormatHelper
{
    public function getFormatDimension($format)
    {
        $return = $format['MEDIA_FORMAT_WIDTH'];
        $return .= ' x ';
        $return .= $format['MEDIA_FORMAT_HEIGHT'] ? $format['MEDIA_FORMAT_HEIGHT'] : t('NDP_HEIGHT_VARIABLE');

        return $return;
    }

    public function getFormatInformation($format, $min = true)
    {
        $return = '';
        $prefix = ($min) ? t('FORMAT_MIN') : '';
        if (!empty($format['MEDIA_FORMAT_WIDTH'])) {
            $return .= t('FORMAT_ATTENDU').t($format['MEDIA_FORMAT_LABEL']);
            $return .= ' | '.$prefix.$this->getFormatDimension($format);
        }

        return $return;
    }
}
