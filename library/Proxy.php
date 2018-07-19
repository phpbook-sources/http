<?php namespace PHPBook\Http;

abstract class Proxy {    

    public static function generate() {
        
        $phpClasses = [];

        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(\PHPBook\Http\Configuration\Request::getControllersPathRoot()));

        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');

        foreach ($phpFiles as $phpFile) {

            require_once $phpFile->getRealPath();

            $content = file_get_contents($phpFile->getRealPath());

            $tokens = token_get_all($content);

            $namespace = '';

            for ($index = 0; isset($tokens[$index]); $index++) {

                if (!isset($tokens[$index][0])) {
                    continue;
                };

                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    };
                };

                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2;
                    $phpClasses[] = $namespace ? '\\'.$namespace.'\\'.$tokens[$index][1] : $tokens[$index][1];
                    break;
                };

            };

        };

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

        $contents = '<?php namespace ' . \PHPBook\Http\Configuration\Request::getProxiesNamespace() . ';' . PHP_EOL . PHP_EOL;
        $contents .= 'abstract class Proxy {' . PHP_EOL . PHP_EOL;
        $contents .= "\t" . 'public static function init() {' . PHP_EOL . PHP_EOL;
        $contents .= $classesProxies . PHP_EOL;
        $contents .= "\t" . '}' . PHP_EOL . PHP_EOL;
        $contents .= '}' . PHP_EOL;

        file_put_contents(\PHPBook\Http\Configuration\Request::getProxiesPathRoot() . DIRECTORY_SEPARATOR . 'Proxy.php', $contents);

    }

    public static function start() {

        $classProxy = '\\' . \PHPBook\Http\Configuration\Request::getProxiesNamespace() . '\\\Proxy'::class;

        $classFile = \PHPBook\Http\Configuration\Request::getProxiesPathRoot() . DIRECTORY_SEPARATOR . 'Proxy.php';

        if ((!class_exists($classProxy)) and (file_exists($classFile))) {
    
            require_once $classFile;

        };

        if (class_exists($classProxy)) {

            $classProxy::init();

        };

    }

}
