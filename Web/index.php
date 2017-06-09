<?php 
	require_once 'inc/header.php';
	require_once 'inc/DataToHtml.php';

	$page = 0;
	if(filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT))
	{
		$page = $_GET['page'];
		if($page < 0)
			$page = 0;
	}
	else
		$page = 0;
?>	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Categories</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
						<?php
							echo DataToHtml::CategoriesToHtml();
						?>
						</div>
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">Products</h2>
						<?php							
							echo DataToHtml::ProductsToHtml(Gestock::getInstance()->getProducts($page * NUMBER_PRODUCTS_SHOWN));
							echo DataToHtml::PaginationToHtml($page);
						?>
					</div><!--features_items-->
				</div>
			</div>
		</div>
	</section>
	<?php require_once 'inc/footer.php'; ?>