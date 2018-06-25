<html>

	<head>
		
		<?php include __DIR__ . '/docs-header-meta.php'; ?>

		<?php include __DIR__ . '/docs-header-style.php'; ?>

		<?php include __DIR__ . '/docs-header-script.php'; ?>	
		
	</head>
	
	<body>

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

							<?php foreach (\PHPBook\Http\Request::getCategories() as $key => $category): ?>
								<div class="link">
									<a href="<?php echo $path; ?><?php echo \PHPBook\Http\Configuration\Directory::getDocs(); ?>/resources/<?php echo $key; ?>" title="<?php echo $category->getName(); ?>"><?php echo $category->getName(); ?></a>
								</div>
							<?php endforeach; ?>

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
					