<?php namespace PHPBook\Http;

abstract class Proxy {    

    private static $started = false;

    private static function clearPathRecursive($dir, $initial = true) { 

        $files = array_diff(scandir($dir), array('.','..')); 

        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? Static::clearPathRecursive("$dir/$file", false) : unlink("$dir/$file"); 
        };

        if (!$initial) {
            return rmdir($dir); 
        };

    }

    public static function generate() {

        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(\PHPBook\Http\Configuration\Request::getControllersPathRoot()));

        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');

        foreach ($phpFiles as $phpFile) {

            require_once $phpFile->getRealPath();

        };

        $phpClasses = get_declared_classes();

        $classesProxies = '';

        foreach($phpClasses as $phpClass) {

            $rc = new \ReflectionClass($phpClass);

            preg_match_all('/@PHPBookHttpRequestCategory{(.*?)}/s', $rc->getDocComment(), $matches);

            foreach($matches[1] as $item) {
                preg_match_all('(["]+[\w]+["]:[\s]*["]+["a-zA-Z\'\d\s"]+["])', $item, $layoutPattern);
                $attributes = json_decode('{' . str_replace('\\', "\\\\", implode(',', $layoutPattern[0])) . '}');
                if ($attributes) {
                    $classesProxies .= "\t" . "\t" . '\PHPBook\Http\Request::setCategory((new \PHPBook\Http\Category)' . PHP_EOL;
                    foreach($attributes as $attribute => $value) {
                        $classesProxies .= "\t" . "\t" . "\t" . '->' . $attribute . '(' . $value . ')' . PHP_EOL;
                    };
                    $classesProxies .= "\t" . "\t" . ');' . PHP_EOL . PHP_EOL;
                };
            };

            foreach($rc->getMethods() as $method) {

                preg_match_all('/@PHPBookHttpRequestResource{(.*?)}/s', $rc->getMethod($method->name)->getDocComment(), $matches);

                foreach($matches[1] as $item) {
                    preg_match_all('(["]+[\w]+["]:[\s]*["].+["])', $item, $layoutPattern);
                    $attributes = json_decode('{' . str_replace('\\', "\\\\", implode(',', $layoutPattern[0])) . '}');
                    if ($attributes) {
                        $classesProxies .= "\t" . "\t" . '\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource)' . PHP_EOL;
                        foreach($attributes as $attribute => $value) {
                            $classesProxies .= "\t" . "\t" . "\t" . '->' . $attribute . '(' . $value . ')' . PHP_EOL;
                        };
                        $classesProxies .= "\t" . "\t" . "\t" . '->' . 'setController' . '(\'' . $phpClass . '\', \'' . $method->name . '\')' . PHP_EOL;
                        $classesProxies .= "\t" . "\t" . ');' . PHP_EOL . PHP_EOL;
                    };
                };

            };

        };

        if (\PHPBook\Http\Configuration\Request::getProxiesNamespace()) {
            $contents = '<?php namespace ' . \PHPBook\Http\Configuration\Request::getProxiesNamespace() . ';' . PHP_EOL . PHP_EOL;
        } else {
            $contents = PHP_EOL;
        };
        $contents .= '// YOU CANNOT EDIT THIS FILE. GENERATED BY PHPBOOK' . PHP_EOL . PHP_EOL;
        $contents .= 'abstract class PHPBook_HTTP_Proxy {' . PHP_EOL . PHP_EOL;
        $contents .= "\t" . 'public static function init() {' . PHP_EOL . PHP_EOL;
        $contents .= $classesProxies . PHP_EOL;
        $contents .= "\t" . '}' . PHP_EOL . PHP_EOL;
        $contents .= '}' . PHP_EOL;

        Static::clearPathRecursive(\PHPBook\Http\Configuration\Request::getProxiesPathRoot());
        
        file_put_contents(\PHPBook\Http\Configuration\Request::getProxiesPathRoot() . DIRECTORY_SEPARATOR . 'PHPBook_HTTP_Proxy.php', $contents);

    }

    public static function start() {

        if (\PHPBook\Http\Configuration\Request::getProxiesNamespace()) {
            $classProxy = '\\' . \PHPBook\Http\Configuration\Request::getProxiesNamespace() . '\\\PHPBook_HTTP_Proxy'::class;
        } else {
            $classProxy = 'PHPBook_HTTP_Proxy';
        };

        $classFile = \PHPBook\Http\Configuration\Request::getProxiesPathRoot() . DIRECTORY_SEPARATOR . 'PHPBook_HTTP_Proxy.php';

        if ((!class_exists($classProxy)) and (file_exists($classFile))) {
    
            require_once $classFile;

        };

        if (class_exists($classProxy)) {

            if (!Static::$started) {

                Static::$started = true;

                $classProxy::init();
                
            };

        };

    }

}
