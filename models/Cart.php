<?php

class Cart {
    public static function addProduct($id){
        $id = intval($id);
        
        $productsInCart = array();
        
        if(isset($_SESSION['products'])){
            $productsInCart = $_SESSION['products'];
        }
        
        if(array_key_exists($id, $productsInCart)){
            $productsInCart[$id] ++;
        } else{
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;
        
        return self::countItems();
        
    }
    public static function countItems(){
        if(isset($_SESSION['products'])){
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity){
                $count = $count + $quantity;
            }
            return $count;
        } else{
            return 0;
        }
    }
    public static function getProducts()
    {
        if (isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return false;
    }
    
    public static function getTotalPrice($products)
    {
        // Получаем массив с идентификаторами и количеством товаров в корзине
        $productsInCart = self::getProducts();
        // Подсчитываем общую стоимость
        $total = 0;
        if ($productsInCart) {
            // Если в корзине не пусто
            // Проходим по переданному в метод массиву товаров
            foreach ($products as $item) {
                // Находим общую стоимость: цена товара * количество товара
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }
        return $total;
    }
    public static function clear()
    {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }
    
    public static function deleteProduct($id){
        if (isset($_SESSION['products'][$id])){
            unset($_SESSION['products'][$id]);
        }
    }
    
    
}
