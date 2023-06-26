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

        function hasSubcategoryActive($category, $currentCategory) {

            $hasSubCategoryActive = false;

            if (isset($category['sub'])) {

                foreach($category['sub'] as $subCategory) {

                    if ($subCategory['key'] == $currentCategory) {

                        $hasSubCategoryActive = true;

                        break;

                    } else {

                        $hasSubCategoryActive = hasSubcategoryActive($subCategory, $currentCategory);

                    }

                }

            }

            return $hasSubCategoryActive;
        }

        function renderCategoryMenuRecursive($path, $categoriesNode, $nestLevel = 0, $currentCategory = null){

            $html = '';

            foreach($categoriesNode as $key => $category) {

                $hasSubCategoryActive = hasSubcategoryActive($category, $currentCategory);

                $subitems = renderCategoryMenuRecursive($path, $category['sub'], $nestLevel + 1, $currentCategory);

                $nestedNavigation = $nestLevel === 0;

                $linkName = 'link-base-' . ($key + 1);

                $buttonExpand = $subitems ? '<span class="link-expand" '.($nestedNavigation ? 'onClick="toggleMenu(this, \''.$linkName.'\')"' : '').'>[+]</span>' : '';

                $html .= '<div class="link" style="margin-left: '.($nestLevel * 10).'px; display: block">'.($nestedNavigation ? $buttonExpand . ' ' : null).'<a href="' . $path . \PHPBook\Http\Configuration\Directory::getDocs() .'/resources/' . $category['key'].'" title="'.$category['name'].'">'.$category['name'].'</a><div '.($nestedNavigation ? 'id="'.($linkName).'" style="display: '.($hasSubCategoryActive ? 'block' : 'none').'"' : '').'>'.$subitems.'</div></div>';

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

                                <?php echo renderCategoryMenuRecursive($path, $categoryMenuRecursive, 0, $value); ?>


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
					