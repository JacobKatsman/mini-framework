<?php

namespace AppName;
use AppName\TemplateEgngine;

class BalanceProcessor
{
    public $template;
    public $path_to_folder_images;
    public $baseUrl;

    const
        CURLOPTION = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_USERAGENT => "test spider",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_MAXREDIRS => 100];

    function __construct()
    {
        $this->template = new TemplateEgngine();
    }

    public function getUrl()
    {
        return $this->template->renderGeTUrlFormTemplate();
    }

    public function getToCurl($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, self::CURLOPTION);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    public function save_from_url($url, $destination)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, self::CURLOPTION);
        $content = curl_exec($ch);
        curl_close($ch);

        file_put_contents($destination, $content);

        return filesize($destination);
    }

    public function parseToCurl($url)
    {
        $content = $this->getToCurl($url);
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($content);
        $dom->saveHTML();
        $images = $dom->getElementsByTagName('img');

        $list = [];
        foreach ($images as $image) {
            $list[] = $image->getAttribute('src');
        }
        return $list;
    }

    // формирование таблицы и подсчет файлов для вывода в шаблон
    public function createTempletObject(string $url)
    {
        $imgCounter = 0;
        $totalSize = 0;
        $listOffiles = [];
        foreach ($this->parseToCurl($url) as $key => $img) {
            $res = parse_url($img);
            $size = $this->save_from_url($this->baseUrl . $res["path"], $this->path_to_folder_images . '/' . basename($res["path"]));
                $totalSize += $size;
                $imgCounter++;
            $listOffiles[] = ["file" => "$this->path_to_folder_images" . basename($img), "size" => $size];
        }
        return ["listFiles" => $listOffiles, "imgCounter" => $imgCounter, "totalSize" => $totalSize];
    }

    public function createFolder()
    {
        $this->path_to_folder_images = getcwd() . '/photo/';
        if (!is_dir($this->path_to_folder_images)) {
            mkdir($this->path_to_folder_images, 0777);
        }
    }

    public function createBaseUrl($url)
    {
        $splitStr = parse_url($url);
        $this->baseUrl = $splitStr["scheme"] . "://" . $splitStr["host"];
    }

    // показать форму  request
    public function requestForm(string $url) {
        // TODO исключение если нет url или он пустой
        $this->createFolder();
        $this->createBaseUrl($url);
        return $this->template->renderTemplate($this->createTempletObject($url));
    }
}
