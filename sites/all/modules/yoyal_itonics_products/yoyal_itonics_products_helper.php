<?php

class YoyalItonicsProductsHelper {
  public static function  yoyal_itonics_products_get_products (){
    $products = array();

    // Fetch products from the 'yoyal_itonics_products' table.
    $query = db_select('yoyal_itonics_products', 'p')
      ->fields('p')
      ->execute();

    // Loop through the query results to build an array of products.
    foreach ($query as $result) {
      $products[] = array(
        'pid' => $result->pid,
        'title' => $result->title,
        // Add other fields here based on your table structure.
        // Example: 'summary' => $result->summary,
        //          'description' => $result->description,
        //          'category' => $result->category,
        //          ... (and so on)
      );
    }

    return $products;
  }
  public static function  yoyal_itonics_products_get_all_categories (){
    $categories = array();

    // Fetch products from the 'yoyal_itonics_products' table.
    $query = db_select('yoyal_itonics_categories', 'c')
      ->fields('c')
      ->execute();

    // Loop through the query results to build an array of products.
    foreach ($query as $result) {
      $categories[] = array(
        'cid' => $result->cid,
        'title' => $result->title,
        'description' => $result->description
      );
    }

    return $categories;
  }
  public static function get_category_list (){
    $all = self::yoyal_itonics_products_get_all_categories();

    $categories = [];
    foreach ($all as $key => $a) {
      $categories[$a['cid']] = $a['title'];
    }
    return $categories;
  }
}
