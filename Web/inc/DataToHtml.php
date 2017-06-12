<?php

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
     * Build the HTML code to build the paging of any file.
     * @param int $page Current page of the file.
     * @param array $actualParametter Any additionnal parametter to add to the URL.
     * @return string The HTML code for the pagination, and the link.
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

    static public function CartProductsToHTML($idUser)
    {
        $htmlToShow = "";
        $total = 0;

        $result = Gestock::getInstance()->getCartProducts($_SESSION['user']['id']);

        foreach($result as $product)
        {
            $htmlToShow .= '<tr>
                <td>
                    <a href="productDetails.php?id=' . $product['idProduct_fk'] . '"><img src="img/products/' . $product['imgName'] . '" alt=""></a>
                </td>
                <td class="cart_description">
                    <h4><a href="productDetails.php?id=' . $product['idProduct_fk'] . '"><b>' . $product['brand'] . '</b> ' . $product['name'] . '</a></h4>
                </td>
                <td class="cart_price">
                    <p>' . $product['price'] . '.-</p>
                </td>
                <td class="cart_quantity">
                    <div class="cart_quantity_button">
                        <a class="cart_quantity_up" href=""> + </a>
                        <input class="cart_quantity_input" type="text" name="quantity" value="' . $product['nbProduct'] . '" autocomplete="off" size="2">
                        <a class="cart_quantity_down" href=""> - </a>
                    </div>
                </td>
                <td class="cart_total">
                    <p class="cart_total_price">' . $product['nbProduct'] * $product['price'] . '.-</p>
                </td>
                <td class="cart_delete">
                    <a class="cart_quantity_delete" href="deleteProductFromCart.php?id=' . $product['idProduct_fk'] . '"><i class="fa fa-times"></i></a>
                </td>
            </tr>';
            $total += $product['nbProduct'] * $product['price'];
        }
        $htmlToShow .= '<tr><td></td><td></td><td></td><td></td><td class="cart_total"><p class="cart_total_price">' . $total . '.-</p></td></tr>';

        return $htmlToShow;
    }
}

?>