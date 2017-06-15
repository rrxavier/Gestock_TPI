<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            DataToHtml.php
# Date :                14.06.17
#--------------------------------------------------------------------------
# Build the HTML code from the data returned by "Gestock".
#
# Version 1.0 :         14.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';

/**
* Class used to build the HTML with the data returned by "Gestock".
*/
class DataToHtml
{
    /**
     * Builds the HTML code to show the Categories on the left side of the site.
     * @return The HTML code for the Categories.
     */
    static public function CategoriesToHtml()
    {							
        $htmlToShow = "";
        foreach(Gestock::getInstance()->getCategories() as $category)
        {
            $htmlToShow .= '<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="category.php?id=' .  $category['id'] . '">' .  $category['name'] . '</a></h4>
                                </div>
                            </div>';
        }
        return $htmlToShow;
    }

    /**
     * Builds the HTML code to show the products where it's needed.
     * @param array $products Array containing all the products to show.
     * @return string The HTML code for the products.
     */
    static public function ProductsToHtml($products)
    {   
        if(count($products) == 0)
            header('Location: index.php');

        $htmlToShow = "";
        foreach($products as $product)
        {
            $htmlToShow .= '<figure class="figure col-sm-4 text-center">
                                <div class="row"><a href="productDetails.php?id=' . $product['id'] . '"><img src="img/products/' . $product['imgName'] . '" class="figure-img img-fluid rounded img-responsive" alt="' . $product['name'] . '"></a></div>
                                <figcaption class="figure-caption text-center"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</figcaption>
                                <figcaption class="figure-caption text-center">' . $product['price'] . '.-</figcaption>
                                <a href="#" class="btn btn-default add-to-cart" onclick="addToCart(' . $product['id'] . ')"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                            </figure>';
        }
        return $htmlToShow;
    }
    
    /**
     * Builds the HTML code to build the paging of any file.
     * @param int $page Current page of the file.
     * @param array $actualParametter Any additionnal parametter to add to the URL.
     * @return string The HTML code for the pagination, and the links.
     */
    static public function PaginationToHtml($page, $actualParametter = array("name" => "", "value" => null))
    {
        $nbPages = Gestock::getInstance()->getNbProducts()[0]['NB_ROWS'] / NUMBER_PRODUCTS_SHOWN;

        $actualParametterHTML = "";
        if($actualParametter['value'] != null)
            $actualParametterHTML = "&" . $actualParametter['name']. '=' . $actualParametter['value'];

        $htmlToShow = '<ul class="pagination">';
        if($page > 0)
            $htmlToShow .= '<li><a href="' . basename($_SERVER['PHP_SELF']) . '?page=' . ($page - 1) . "&" . $actualParametterHTML . '" aria-label="Previous">&laquo;</a></li>';
        else 
            $htmlToShow .= '<li class="disabled"><span aria-label="Previous">&laquo;</span></li>';

        for($i = 0; $i < $nbPages; $i++)
            $htmlToShow .= '<li class="' . ($page == $i ? "active" : "") . '"><a href="' . basename($_SERVER['PHP_SELF']) . '?page=' . $i . "&" . $actualParametterHTML . '">' . ($i + 1) . '</a></li>';

        if($page < ($nbPages - 1))
            $htmlToShow .= '<li><a href="' . basename($_SERVER['PHP_SELF']) . '?page=' . ($page + 1) . "&" . $actualParametterHTML . '" aria-label="Next">&raquo;</a></li></ul>';
        else
            $htmlToShow .= '<li class="disabled"><span aria-label="Next">&raquo;</span></li></ul>';

        return $htmlToShow;
    }

    /**
     * Builds the HTML code to show in the cart page.
     * @param type $idUser ID of the owner of the cart.
     * @return string The built HTML code.
     */
    static public function CartProductsToHTML($idUser)
    {
        $htmlToShow = "";
        $total = 0;

        $result = Gestock::getInstance()->getCartProducts($idUser);

        foreach($result as $product)
        {
            $htmlToShow .= '<tr>
                <td>
                    <a href="productDetails.php?id=' . $product['id'] . '"><img src="img/products/' . $product['imgName'] . '" alt=""></a>
                </td>
                <td>
                    <h4><a href="productDetails.php?id=' . $product['id'] . '"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</a></h4>
                </td>
                <td class="cart_price">
                    <p>' . $product['price'] . '.-</p>
                </td>
                <td class="cart_quantity text-center">
                    <div class="row">
                        <a class="cart_quantity_up col-sm-2 col-sm-offset-2" href="cart.php" onclick="addToCartFromCart(' . $product['id'] . ')"> + </a>
                        <div class="col-sm-4">' . $product['cartQuantity'] . ' <b>(' . $product['stockQuantity'] . ')</b></div>
                        <a class="cart_quantity_down col-sm-2" href="cart.php" onclick="addToCartFromCart(' . $product['id'] . ', -1)"> - </a>
                    </div>
                </td>
                <td class="cart_total text-center">
                    <p class="cart_total_price">' . $product['cartQuantity'] * $product['price'] . '.-</p>
                </td>
                <td class="cart_delete">
                    <a class="cart_quantity_delete" href="deleteProductFromCart.php?id=' . $product['id'] . '"><i class="fa fa-times"></i></a>
                </td>
            </tr>';
            $total += $product['cartQuantity'] * $product['price'];
        }
        $htmlToShow .= '<tr><td></td><td></td><td></td><td></td><td class="cart_total"><p class="cart_total_price text-center">' . $total . '.-</p></td></tr>';
        $htmlToShow .= '<tr><td colspan="6" class="text-center"><a href="passOrder.php" class="btn btn-default btnOrder"><h4>Order</h4></a></td></tr>';

        return $htmlToShow;
    }

    /**
     * Builds the HTML code to show in the account page, under the "Cart preview" section.
     * @param type $idUser ID of the owner of the cart.
     * @return string The build HTML code
     */
    static public function CartPreview($idUser)
    {
        $htmlToShow = "";
        $htmlToShow .= '<table>';
        foreach(Gestock::getInstance()->getFirstCartProducts($idUser) as $product)
            $htmlToShow .= '<tr><td class="col-sm-2"><img src="img/products/' . $product['imgName'] . '"></td><td class="col-sm-6"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</td><td class="cart_price col-sm-3"><p>' . $product['price'] . '.-</p></td></tr>';
        $htmlToShow .= '</table>';
        $htmlToShow .= '<div class="text-center"><a href="cart.php">Go to cart</a></div>';

        return $htmlToShow;
    }

    static public function PreviousOrdersPreview($idUser)
    {
        $htmlToShow = "";
        $htmlToShow .= '<table>';
        foreach(Gestock::getInstance()->getFirstPreviousOrders($idUser) as $order)
            $htmlToShow .= '<tr><td class="col-sm-9 text-center"><b>' . $order['dateOrder'] . '</b></td><td><a href="previousOrderDetails.php?id=' . $order['id'] . '">Check details</a></td></tr>';
        $htmlToShow .= '</table>';
        $htmlToShow .= '<div class="text-center"><a href="previousOrders.php">Get the full list</a></div>';

        return $htmlToShow;
    }

    static public function PreviousOrderProductsToHTML($idUser)
    {
        $products = Gestock::getInstance()->getPreviousOrderProducts($_SESSION['user']['id'], $_GET['id']);
        if(count($products) == 0)
            header('Location: index.php');

        $htmlToShow = "";
        $total = 0;
        foreach($products as $product)
        {
            $htmlToShow .=  '<tr>
                <td class="col-sm-1">
                    <a href="productDetails.php?id=' . $product['id'] . '"><img src="img/products/' . $product['imgName'] . '" alt=""></a>
                </td>
                <td class="cart_description col-sm-4">
                    <h4><a href="productDetails.php?id=' . $product['id'] . '"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</a></h4>
                </td>
                <td class="col-sm-2">
                    <h4>' . $product['category'] . '</h4>
                </td>
                <td class="cart_price col-sm-2">
                    <p>' . $product['price'] . '.-</p>
                </td>
                <td class="cart_quantity col-sm-1">
                    <div class="row text-center">
                        <div class="col-sm-6">' . $product['cartQuantity'] . '</div>
                    </div>
                </td>
                <td class="cart_total col-sm-2 text-center">
                    <p class="cart_total_price">' . $product['cartQuantity'] * $product['price'] . '.-</p>
                </td>
            </tr>';
            $total += $product['cartQuantity'] * $product['price'];
        }
        $htmlToShow .= '<tr><td></td><td></td><td></td><td></td><td></td><td class="cart_total"><p class="cart_total_price text-center">' . $total . '.-</p></td></tr>';   
        return $htmlToShow;
    }

    static public function PreviousOrdersToHTML($idUser)
    {
        $htmlToShow = '';
        foreach(Gestock::getInstance()->getPreviousOrders($_SESSION['user']['id']) as $order)
            $htmlToShow .= '<tr><td class="col-sm-6 text-center"><h3>' . $order['dateOrder'] . '</h3></td><td class="col-sm-6 text-center"><a href="previousOrderDetails.php?id=' . $order['id'] . '">Check details</a></td></tr>';
        return $htmlToShow;
    }

    static public function AdminProductsToHTML($page, $searchToken = "")
    {
        $products = Gestock::getInstance()->getProductsLIKE($page * NUMBER_PRODUCTS_SHOWN, $searchToken);
        $htmlToShow = "";
        foreach($products as $product)
        {
            $htmlToShow .=  '<tr>
                <td class="">
                    <a href="productDetails.php?id=' . $product['id'] . '"><img src="img/products/' . $product['imgName'] . '" alt=""></a>
                </td>
                <td class="cart_description">
                    <h4><a href="productDetails.php?id=' . $product['id'] . '"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</a></h4>
                </td>
                <td class="col-sm-2">
                    <h4>' . $product['category'] . '</h4>
                </td>
                <td class="cart_price">
                    <p>' . $product['price'] . '.-</p>
                </td>
                <td class="cart_quantity">
                    <div class="row text-center">
                        <div>' . $product['stockQuantity'] . '</div>
                    </div>
                </td>
                <td class="cart_total text-center">
                    <p class="cart_total_price"><div class="col-sm-6"><a href="adminProduct.php?id=' . $product['id'] . '&mode=modify">Modify</a></div><div class="col-sm-6"><a href="deleteProduct.php?id=' . $product['id'] . '">Delete</a></div></p>
                </td>
            </tr>';
        }
        return $htmlToShow;
    }
}

?>