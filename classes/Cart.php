<?php

class Cart {
    public static function add($domain, $price) {
        $_SESSION['cart'][$domain] = $price;
    }

    public static function remove($domain) {
        if (isset($_SESSION['cart'][$domain])) {
            unset($_SESSION['cart'][$domain]);
        }
    }

    public static function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    public static function getTotal() {
        return array_sum(self::getCart());
    }

    public static function clearCart() {
        $_SESSION['cart'] = [];
    }
}
?>
