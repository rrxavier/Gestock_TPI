<?php 
	require_once 'inc/header.php';
	require_once 'inc/Gestock.php';
?>	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Categories</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
						<?php
							$page = 0;
							if(filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT))
								$page = $_GET['page'];
							else
								$page = 0;

							$gestock = new Gestock();							
							$htmlToShow = "";
							foreach($gestock->getCategories() as $category)
							{
								$htmlToShow .= '<div class="panel panel-default">
													<div class="panel-heading">
														<h4 class="panel-title"><a href="#">' .  $category['name'] . '</a></h4>
													</div>
												</div>';
							}
							echo $htmlToShow;
						?>
						</div>
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">Produits</h2>
						<?php							
							$htmlToShow = "";
							foreach($gestock->getProducts($page * NUMBER_PRODUCTS_SHOWN) as $product)
							{
								$htmlToShow .= '<figure class="figure col-sm-4 text-center">
													<div class="row"><img src="img/products/' . $product['imgName'] . '" class="figure-img img-fluid rounded img-responsive" alt="' . $product['name'] . '"></div>
													<figcaption class="figure-caption text-center"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</figcaption>
													<figcaption class="figure-caption text-center">' . $product['price'] . '.-</figcaption>
													<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
												</figure>';
							}
							echo $htmlToShow;
						?>
						<ul class="pagination">
							<?php
								$htmlToShow = "";
								$nbPages = $gestock->getNbProducts()[0]['NB_ROWS'] / NUMBER_PRODUCTS_SHOWN;
								$htmlToShow .= '<li class="' . ($page > 0 ? "" : "disabled") . '"><a href="index.php?page=' . ($page > 0 ? ($page - 1) : ($page . "#")) . '" aria-label="Previous">&laquo;</a></li>';
								for($i = 0; $i < $nbPages; $i++)
								{
									$htmlToShow .= '<li class="' . ($page == $i ? "active" : "") . '"><a href="index.php?page=' . $i . '">' . ($i + 1) . '</a></li>';
								}
								$htmlToShow .= '<li class="' . ($page < ($nbPages - 1) ? "" : "disabled") . '"><a href="index.php?page=' . ($page < ($nbPages - 1) ? $page + 1 : ($page . "#")) . '" aria-label="Next">&raquo;</a></li>';
								echo $htmlToShow;
							?>
						</ul>
					</div><!--features_items-->
				</div>
			</div>
		</div>
	</section>
	<?php require_once 'inc/footer.php'; ?>