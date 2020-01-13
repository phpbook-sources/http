
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

        <?php $relationUsage = []; ?>

        <?php
        if ($resource->getMiddlewareCode()) {
            $middlewareCodeParts = explode(':', $resource->getMiddlewareCode());
            if (count($middlewareCodeParts) > 1) {
                list($middlewareCode, $middlewareParameters) = explode(':', $resource->getMiddlewareCode());
            } else {
                list($middlewareCode) = explode(':', $resource->getMiddlewareCode());
                $middlewareParameters = null;
            };
            $middleware = \PHPBook\Http\Request::getMiddlewareSchema($middlewareCode);
        } else {
            $middleware = null;
        }
        ?>
        <?php if ($middleware): ?>
            <?php if ($middleware->getRelation()): ?>
                <?php foreach($middleware->getRelation() as $relation): ?>
                    <?php $relationUsage[] = $relation ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($resource->getRelation()): ?>
            <?php foreach($resource->getRelation() as $relation): ?>
                <?php $relationUsage[] = $relation ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (count($relationUsage) > 0): ?>
            <div class="fieldset">
                <div class="name">
                    Usage Relations
                </div>
                <div class="data">
                    <?php foreach($relationUsage as $relationUsageRaw): ?>
                        <?php list($relationType, $relationUri) = $relationUsageRaw ?>
                        <?php foreach (\PHPBook\Http\Request::getResources() as $resourceUsage): ?>
                            <?php if (($resourceUsage->getUri() == $relationUri) and ($resourceUsage->getType() == $relationType)): ?>
                                <strong><?php echo $relationType; ?></strong>
                                <?php echo $relationUri; ?> - <?php echo $resourceUsage->getNotes() ?> <br />
                            <?php endif; ?>
                        <?php endforeach; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

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

        <?php if ($resource->getInputQuery()): ?>
            <div class="fieldset">
                <div class="name">
                    Query Input
                </div>
                <div class="data">
                    <xmp><?php
                        list($type, $element, $rules) = $resource->getInputQuery();
                        $inputQuery = new \PHPBook\Http\Query(new $type($element, 'InputQuery'), $rules);
                        echo \PHPBook\Http\Dispatch::schema($inputQuery->schema());?></xmp>
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