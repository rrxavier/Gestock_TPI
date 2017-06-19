<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            productDetails.php
# Date :                7.06.17
#--------------------------------------------------------------------------
# Shows all details of a selected product.
#
# Version 1.0 :         7.06.17
#--------------------------------------------------------------------------

	require_once 'inc/header.php';
	require_once 'inc/DataToHtml.php';

	$idProduct = -1;
    if(filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT))
        $idProduct = $_GET['id'];
    else
        header("Location: index.php");
	
	$product = Gestock::getInstance()->getProductById($idProduct)[0];
	if($product === null)
		header("Location: index.php");
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
					<div class="product-details"><!--product-details-->
						<div class="col-sm-5 left-img pull-center">
							<img src=<?php echo '"img/products/' . $product['imgName'] . '"' ?> alt="" />
						</div>
						<div class="col-sm-7">
							<div class="product-information"><!--/product-information-->
								<h2><?php echo $product['name']; ?></h2>
								<span>
									<span><?php echo $product['price']; ?>.-</span>
									<button type="button" class="btn btn-fefault cart" <?php echo 'onclick=addToCart(' . $product['id'] . ')'; ?>>
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
								</span>
								<p><b>Brand:</b> <?php echo $product['brand']; ?></p>
								<p><b>Category:</b> <?php echo $product['category']; ?></p>
								<p><b>Availability:</b> 
								<?php

									if($product['stockQuantity'] > $product['alertQuantity'])
										echo '<strong class="text-success">In Stock</strong>';
									else if($product['stockQuantity'] <= $product['alertQuantity'] && $product['stockQuantity'] != 0)
										echo '<strong class="text-warning">Low stock</strong>';
									else
										echo '<strong class="text-danger">Out of stock</strong>';

								?>
								</p>
							</div><!--/product-information-->
						</div>
					</div><!--/product-details-->					
				</div>
			</div>
		</div>
	</section>
	
	<?php
		require_once 'inc/footer.php';
	?>