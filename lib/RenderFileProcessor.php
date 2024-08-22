<?php
namespace AppName;

use AppName\TemplateEgngine;

class RenderFileProcessor
{
    const
        EXTENSION  = [
          "git" => "image/gif",
          "jpg"  => "image/jpeg",
          "jpeg"  => "image/jpeg",
          "pjpeg" => "image/pjpeg",
          "png" => "image/png",
          "svg" => "image/svg+xml",
          "tiff" => "image/tiff",
          "ico" => "image/vnd.microsoft.icon",
          "wbmp" => "image/vnd.wap.wbmp",
          "webp" => "image/webp"];

    public function getImagefile(string $pathName)
    {
        //http://127.0.0.1:8080/showimage?file=photo/ledik-1-photo_2024-08-07_15-28-47.jpg
        $path_info = pathinfo($pathName);
        // логика для графических расширений
        if (self::EXTENSION[$path_info['extension']] !='') {
            header("content-type: ".self::EXTENSION[$path_info['extension']]."; charset=UTF-8");
            readfile($_SERVER['DOCUMENT_ROOT'] . "/" . $pathName);
        }
    }
}
