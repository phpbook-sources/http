<?php namespace PHPBook\Http;

abstract class Dispatch {
    
    public static function content($data) {
        $body = Configuration\Output::getContent();
        array_walk_recursive($body, function(&$value) use($data) {
            if ($value == '@') {$value = $data;};
        });
        Static::document($body);
    }

    public static function exception(String $message, Int $code = 500) {
        $body = Configuration\Output::getException();
        array_walk_recursive($body, function(&$value) use($message) {
            if ($value == '@') {$value = $message;};
        });
        Static::document($body, $code);
    }

    public static function document($body, Int $code = 200) {
        switch(strtolower(Configuration\Output::getType())) {
            case 'json':
                http_response_code($code);
                header('Content-Type: application/json');
                echo Export::json($body);
                break;
            default:
                http_response_code($code);
                header('Content-Type: text/xml');
				echo Export::xml(['document' => $body]);
                break;
        };
    }

    public static function cache(Int $hours) {
        Header("Cache-Control: max-age=" . $hours * 60 * 60);
        Header("Expires: " . gmdate("D, d M Y H:i:s", strtotime('+' . $hours .' hours')) . " GMT");
    }

    public static function buffer(String $buffer) {
        $mime = (new \FInfo(FILEINFO_MIME_TYPE))->buffer($buffer);
        header('Content-Type: ' . $mime);
        echo $buffer;
    }  
    
    public static function schema($schema) {
        switch(strtolower(Configuration\Output::getType())) {
            case 'json':
                return Export::json($schema);
                break;
            default:
                return Export::xml(['document' => $schema]);
                break;
        };
    }

}
