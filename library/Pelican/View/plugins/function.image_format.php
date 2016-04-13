<?php
    function smarty_function_image_format($params, &$smarty)
    {
        $path = substr($params['path'], 0, strrpos($params['path'], '.'));
        $extension = substr($params['path'], strrpos($params['path'], '.')+1, strlen($params['path']));
        if ($params['format'] != '') {
            $imagePath = $path.'.'.$params['format'].'.'.$extension;
        } else {
            $imagePath = $path.'.'.$extension;
        }

        return $imagePath;
    }
