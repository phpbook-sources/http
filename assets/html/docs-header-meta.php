
    <?php $path = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . (dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : '/'); ?>

    <title><?php echo \PHPBook\Http\Configuration\Meta::getName(); ?> HTTP</title>

    <meta name="robots" content="noindex" />

    <meta name="googlebot" content="noindex" />