<?php
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
									<label>Quantity:</label>
									<input type="text" value="3" />
									<button type="button" class="btn btn-fefault cart">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
								</span>
								<p><b>Brand:</b> <?php echo $product['brand']; ?></p>
								<p><b>Category:</b> <?php echo $product['category']; ?></p>
								<p><b>Availability:</b> In Stock</p>
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