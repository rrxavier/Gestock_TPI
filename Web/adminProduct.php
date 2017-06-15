<?php
require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$mode = filter_input(INPUT_GET, "mode");
$htmlMode = "";
$product = array();

if($mode)
{
    if($mode == "modify")
    {
        $idProduct = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if(!$idProduct)
            header('Location: login.php');

        $product = Gestock::getInstance()->getProductById($idProduct);
        if(count($product) == 0)
            header('Location: login.php');
        $product = $product[0];
        $htmlMode = "Modify";
    }
    else if($mode == "add")
        $htmlMode = "Add";
    
}
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <form <?php echo 'action="' . ($mode == "modify" ? "modifyProduct.php?id=" . $idProduct : "addProduct.php") . '"' ?> method="POST" enctype="multipart/form-data">
            <table class="table table-condensed">
            <div class="row">
                <tbody>
                    <tr><td>Name: </td><td><input required type="text" name="productName" id="productName" value=<?php echo '"' . ($mode == "modify" ? $product['name'] : "") . '"'; ?>></td></tr>
                    <tr><td>Brand: </td><td><input required type="text" name="productBrand" value=<?php echo '"' . ($mode == "modify" ? $product['brand'] : "") . '"'; ?>></td></tr>
                    <tr><td>Price: </td><td><input required type="text" name="productPrice" value=<?php echo '"' . ($mode == "modify" ? $product['price'] : "") . '"'; ?>></td></tr>
                    <tr>
                        <td>
                            Category: 
                        </td>
                        <td>
                            <select required name="productCategory">
                                <?php 
                                    foreach(Gestock::getInstance()->getCategories() as $category)
                                        echo '<option value="' . $category['id'] . '"' . ($mode == "modify" && $category['name'] == $product['category'] ? ' selected="selected"' : '') . '>' . $category['name'] . '</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td>Quantity: </td><td><input required type="text" name="productQuantity" value=<?php echo '"' . ($mode == "modify" ? $product['stockQuantity'] : "") . '"'; ?>></td></tr>
                    <tr><td>Image: </td><td><input <?php echo ($mode == "modify" ? '' : 'required'); ?> type="file" name="productImage"></td><td><span><?php echo ($mode == "modify" ? '(Now : img/products/' . $product['imgName'] . ')' : ""); ?></span></td></tr>
                    <tr>
                        <td>
                            Stock: 
                        </td>
                        <td>
                            <select required name="productStock">
                                <?php 
                                    foreach(Gestock::getInstance()->getStocks() as $stock)
                                        echo '<option value="' . $stock['id'] . '"' . ($mode == "modify" && $stock['id'] == $product['idStock'] ? ' selected="selected"' : '') . '>' . $stock['shelf'] . '</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td colspan="3" class="text-center"><input type="submit" name="sender" value=<?php echo $htmlMode; ?>></td></tr>
                </tbody>
            </div>
            </table>
        </form>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>