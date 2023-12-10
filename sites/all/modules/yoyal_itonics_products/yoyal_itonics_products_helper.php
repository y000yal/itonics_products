<?php

class YoyalItonicsProductsHelper
{

//  public $product_db_name = 'yoyal_itonics_products';
//  public $category_db_name = 'yoyal_itonics_categories';
//  public $media_db_name = 'yoyal_itonics_media';
//  public $product_category_db_name = 'yoyal_itonics_product_categories';
  public static function get_products()
  {
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
        'owner_email' => $result->owner_email,
        'expiry_date' => date('Y-m-d H:i:s', $result->expiry_date),
        'image' => $result->image,
      );
    }

    return $products;
  }

  public static function get_all_categories($cids = null)
  {
    $categories = array();
    // Fetch products from the 'yoyal_itonics_products' table.
    $query = db_select('yoyal_itonics_categories', 'c')
      ->fields('c');
    if (!empty($cids)) {
      $query->condition('cid', $cids, 'in');
    }
    $result = $query->execute();
    // Loop through the query results to build an array of products.
    foreach ($result as $res) {
      $categories[] = array(
        'cid' => $res->cid,
        'title' => $res->title,
        'description' => $res->description
      );
    }

    return $categories;
  }

  public static function get_category_list()
  {
    $all = self::get_all_categories();

    $categories = [];
    foreach ($all as $key => $a) {
      $categories[$a['cid']] = $a['title'];
    }
    return $categories;
  }


  public static function save_products($product_details)
  {
    $pid = db_insert('yoyal_itonics_products')
      ->fields($product_details)
      ->execute();
    return $pid;
  }

  public static function edit_product($product_details, $pid)
  {
    return db_update('yoyal_itonics_products')
      ->fields($product_details)
      ->condition('pid', $pid, '=')
      ->execute();
  }

  public static function save_product_categories($values, $pid)
  {
    if (!empty($values['category'])) {
      foreach ($values['category'] as $val) {
        $cat_data = [
          'product_id' => $pid,
          'category_id' => $val
        ];
        db_insert('yoyal_itonics_product_categories')
          ->fields($cat_data)
          ->execute();
      }
    }
  }

  public static function edit_product_categories($values, $pid)
  {
    self::delete_product_categories($pid);
    self::save_product_categories($values, $pid);
  }

  public static function delete_product_categories($pid)
  {
    db_delete('yoyal_itonics_product_categories')
      ->condition('product_id', $pid, '=')
      ->execute();
  }

  public static function get_single_product($pid)
  {

    $product = [];
    $final_product = [];
    $all_categories = [];
    // Fetch products from the 'yoyal_itonics_products' table.
    $query = db_select('yoyal_itonics_products', 'p')
      ->fields('p')
      ->condition('p.pid', $pid, '=');
    $query->leftJoin('yoyal_itonics_product_categories', 'pc', 'p.pid = pc.product_id');
    $query->fields('pc', array('category_id')); // Replace with your field names from table 2
// Replace with your field names from table 2
    $result = $query->execute();

    // Loop through the query results to build an array of products.
    foreach ($result as $res) {
      $product[] = array(
        'pid' => $res->pid,
        'title' => $res->title,
        'description' => $res->description,
        'summary' => $res->summary,
        'owner_email' => $res->owner_email,
        'type' => $res->type,
        'expiry_date' => date('Y-m-d H:i:s', $res->expiry_date),
        'image' => $res->image,
        'category_id' => $res->category_id,
      );
    }
    $all_categories = [];
    if (count($product) > 1) {
      foreach ($product as $k => $p) {
        $all_categories[] = $p['category_id'];
      }
    }
    $final_product = $product[0];;
    $final_product['category'] = $all_categories;

    return $final_product;
  }

  public static function delete_product($pid)
  {
    return db_delete("yoyal_itonics_products")
      ->condition('pid', $pid, '=')
      ->execute();

  }

  public static function get_product_categories_ids($pid)
  {
    $product_categories = array();
    $query = db_select('yoyal_itonics_product_categories', 'pc')
      ->fields('pc')
      ->condition('product_id', $pid, '=')
      ->execute();

    // Loop through the query results to build an array of products.
    foreach ($query as $result) {
      $product_categories[] = $result->category_id;
    }

    return $product_categories;
  }
}
