
<?php $expression = urldecode($value); ?>

<div class="title">
    Search "<?php echo $expression ?>"
</div>

<p>

    <?php foreach (\PHPBook\Http\Request::getResources() as $key => $resource): ?>

        <?php if (
            (strpos(strtolower($resource->getUri()), strtolower($expression)) === false) and
            (strpos(strtolower($resource->getNotes()), strtolower($expression)) === false) and
            (strpos(strtolower($resource->getType()), strtolower($expression)) === false)
        ): ?>
            <?php continue; ?>
        <?php endif; ?>

        <div class="subtitle">
            <?php echo $resource->getUri(); ?>
        </div>

        <p>
            <strong><?php echo $resource->getNotes(); ?></strong>
        </p>
        
        <div class="fieldset">
            <div class="name">
                <?php echo strtoupper($resource->getType()); ?>
            </div>
            <div class="data">
                <span class="label-info"><?php echo $path . \PHPBook\Http\Configuration\Directory::getApp() . '/'; ?></span><span class="label-important"><?php echo $resource->getUri(); ?></span>
            </div>							
        </div>

         <?php if ($resource->getMiddlewareCode()): ?>
            <div class="fieldset">
                <div class="name">
                    Header Input
                </div>
                <div class="data">
                    <?php
                        $middlewareCodeParts = explode(':', $resource->getMiddlewareCode());
                        if (count($middlewareCodeParts) > 1) {
                            list($middlewareCode, $middlewareParameters) = explode(':', $resource->getMiddlewareCode());
                        } else {
                            list($middlewareCode) = explode(':', $resource->getMiddlewareCode());
                            $middlewareParameters = null;
                        };
                        $middleware = \PHPBook\Http\Request::getMiddlewareSchema($middlewareCode);
                        if ($middleware) {
                        echo $middleware->getName() . '<br />';
                        echo (count($middlewareParameters) > 0) ? '<strong>internal middleware parameters</strong> [' . $middlewareParameters . ']': '';
                        list($type, $element, $rules) = $middleware->getInputHeader();
                        $inputHeader = new \PHPBook\Http\Query(new $type($element, 'InputHeader'), $rules);
                        echo '<xmp>' . \PHPBook\Http\Dispatch::schema($inputHeader->schema()) . '</xmp>';}?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($resource->getInputUri()): ?>
            <div class="fieldset">
                <div class="name">
                    URI Input
                </div>
                <div class="data">
                    <xmp><?php
                        list($type, $element, $rules) = $resource->getInputUri();
                        $inputUri = new \PHPBook\Http\Query(new $type($element, 'InputURI'), $rules);
                        echo \PHPBook\Http\Dispatch::schema($inputUri->schema());?></xmp>
                </div>                          
            </div>
        <?php endif; ?>

        <?php if ($resource->getInputBody()): ?>
            <div class="fieldset">
                <div class="name">
                    Body Input
                </div>
                <div class="data">
                    <xmp><?php
                        list($type, $element, $rules) = $resource->getInputBody();
                        $inputBody = new \PHPBook\Http\Query(new $type($element, 'InputBody'), $rules);
                        echo \PHPBook\Http\Dispatch::schema($inputBody->schema());?></xmp>
                </div>                          
            </div>
        <?php endif; ?>

        <?php if ($resource->getOutput()): ?>

             <div class="fieldset">
                <div class="name">
                    Output
                </div>
                <div class="data">
                    <xmp><?php echo \PHPBook\Http\Dispatch::schema(\PHPBook\Http\Configuration\Output::getContent());?>
                    </xmp>
                    <strong>where output @ is</strong>
                    <xmp><?php
                        list($type, $element, $rules) = $resource->getOutput();
                        $output = new \PHPBook\Http\Query(new $type($element, 'Output'), $rules);
                        echo \PHPBook\Http\Dispatch::schema($output->schema());?></xmp>                            
                </div>						
            </div>

        <?php endif; ?>
        
        <div class="fieldset">
            <div class="name">
                Output Exception
            </div>
            <div class="data">
                <xmp><?php echo \PHPBook\Http\Dispatch::schema(\PHPBook\Http\Configuration\Output::getException()); ?></xmp>
                <strong>where ouput exception @ is equal the string message</strong>
            </div>	
        </div>
                
        <hr />

    <?php endforeach; ?>
    
</p>