<html>

	<head>
		
		<?php include __DIR__ . '/docs-header-meta.php'; ?>

		<?php include __DIR__ . '/docs-header-style.php'; ?>

		<?php include __DIR__ . '/docs-header-script.php'; ?>	
		
	</head>
	
	<body>

        <?php

        function getCategoryMenuRecursive($categoryNest = null, $key = null) {

            if ($categoryNest) {

                $menuItem = [
                    'name' => $categoryNest->getName(),
                    'key' => $key,
                    'sub' => []
                ];

                if (count(\PHPBook\Http\Request::getCategories()) > 0) {
                    foreach (\PHPBook\Http\Request::getCategories() as $keyChild => $categoryChild) {
                        if ($categoryChild->getMainResourceCategoryCode() == $categoryNest->getCode()) {
                            $menuItem['sub'][] = getCategoryMenuRecursive($categoryChild, $keyChild);
                        }
                    }
                }

                return $menuItem;

            } else {

                $menus = [];

                if (count(\PHPBook\Http\Request::getCategories()) > 0) {
                    foreach (\PHPBook\Http\Request::getCategories() as $key => $category) {
                        if (!$category->getMainResourceCategoryCode()) {
                            $menus[] = getCategoryMenuRecursive($category, $key);
                        }
                    }
                }

                return $menus;

            }

        }

        function renderCategoryMenuRecursive($path, $categoriesNode, $nestLevel = 0){

            $html = '';

            foreach($categoriesNode as $key => $category) {

                $subitems = renderCategoryMenuRecursive($path, $category['sub'], $nestLevel + 1);

                $nestedNavigation = $nestLevel === 0;

                $linkName = 'link-base-' . ($key + 1);

                $buttonExpand = $subitems ? '<span class="link-expand" '.($nestedNavigation ? 'onClick="toggleMenu(this, \''.$linkName.'\')"' : '').'>[+]</span>' : '';

                $html .= '<div class="link" style="margin-left: '.($nestLevel * 10).'px; display: block">'.($nestedNavigation ? $buttonExpand . ' ' : null).'<a href="' . $path . \PHPBook\Http\Configuration\Directory::getDocs() .'/resources/' . $category['key'].'" title="'.$category['name'].'">'.$category['name'].'</a><div '.($nestedNavigation ? 'id="'.($linkName).'" style="display: none"' : '').'>'.$subitems.'</div></div>';

            }

            return $html;
        }

        $categoryMenuRecursive = getCategoryMenuRecursive();

        ?>

		<div class="page">

			<div class="side-menu">

				<div class="container">

					<div class="title">
						<a href="<?php echo $path; ?><?php echo \PHPBook\Http\Configuration\Directory::getDocs(); ?>">
							<?php echo \PHPBook\Http\Configuration\Meta::getName(); ?> HTTP
						</a>
						<a title="Menu" href="#" onClick="return toggleMenuShortScreen()" class="hidden-large-screen">
							<span class="icon">≡</span> 
							<span class="label">Menu</span> 
						</a>
					</div>

					<div id="menu">

						<div class="subtitle">
							Search
						</div>

						<div class="search">
							<form onSubmit="return searchResources()">
								<input type="text" id="searcher" placeholder="Search..." name="search" />
							</form>
						</div>

						<div class="subtitle">
							Resources
						</div>

						<div class="menu">

							<?php if (count($categoryMenuRecursive) > 0): ?>

                                <?php echo renderCategoryMenuRecursive($path, $categoryMenuRecursive); ?>


                            <?php else: ?>

								<strong>There is no Resources!</strong>

							<?php endif; ?>

						</div>
						
					</div>

				</div>

			</div>
				
			<div class="side-content">

				<div class="top-bar">
					
					<div class="container">

						<div class="menu">
					
							<div class="link">
								<a href="#" onclick="return false;" title="Resources">Resources</a>
							</div>
							<div class="link active">
								<a href="#" onclick="return false;" title="Version <?php echo \PHPBook\Http\Configuration\Meta::getVersion(); ?>">Version <?php echo \PHPBook\Http\Configuration\Meta::getVersion(); ?></a>
							</div>
							
						</div>

					</div>
					
				</div>
				
				<div class="content">

					<div class="container">
					