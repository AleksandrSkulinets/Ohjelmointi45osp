<?php
function getCartItemCount() {
    if (isset($_SESSION['cart'])) {
        return  array_sum($_SESSION['cart']);
    } else {
        return 0;
    }
}

?>