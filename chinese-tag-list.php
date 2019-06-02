<?php
/*
Plugin Name: Chinese Tag List
Plugin URI:
Description: 根據拼音首字母排序所有TAG並顯示
Author: Mr.Twister(Sardo)
Author URI: https://sardo.work
Version: 0.0.1
*/

require_once __DIR__ . '/vendor/pinyin/Pinyin.php';

add_shortcode('add_chinese_tags', 'chinese_tag_list_load_tags');
add_action('rest_api_init', function () {
  register_rest_route('chinese-tag-list/v1', '/tags', array(
    'methods' => 'GET',
    'callback' => 'chinese_tag_list_load_tags_api',
  ));
});

function chinese_tag_list_load_tags(){
  $origin_tags = get_tags();
  $sorted_tags = _chinese_tag_list_sort_tags($origin_tags);
  echo '<h2>按拼音排序：</h2>';
  foreach($sorted_tags as $key => $tags){
    echo '<h3>' . $key . '</h3>';
    echo '<ul class="tag_list">';
    foreach($tags as $tag){
      echo '<li class="tag">' .
      '<a href="' . $tag['link'] . '">' . $tag['name'] . '</a></li>';
    }
    echo '</ul>';
  }
}

function chinese_tag_list_load_tags_api(){
  $origin_tags = get_tags();
  $sorted_tags = _chinese_tag_list_sort_tags($origin_tags);
  return $sorted_tags;
}

function _chinese_tag_list_sort_tags($tags){
  $sorted_tags = array();
  foreach($tags as $tag){
    $tag_name = $tag->name;
    $tag_link = get_tag_link($tag->term_id);
    $first_char = mb_substr($tag_name, 0, 1);
    $short_pinyin = Pinyin::getShortPinyin($first_char);
    $sorted_tags[$short_pinyin][] = array(
      'name' => $tag_name,
      'link' => $tag_link,
    );
  }
  ksort($sorted_tags);
  return $sorted_tags;
}
?>
