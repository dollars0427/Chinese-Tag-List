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

function chinese_tag_list_load_tags(){
  $origin_tags = get_tags();
  $sorted_tags = _chinese_tag_list_sort_tags($origin_tags);
  echo '<h2>按拼音排序：</h2>';
  foreach($sorted_tags as $key => $tags){
    echo '<h3>' . $key . '</h3>';
    echo '<ul class="tag_list">';
    foreach($tags as $tag){
      echo '<li class="tag">'. $tag . '</li>';
    }
    echo '</ul>';
  }
}

function _chinese_tag_list_sort_tags($tags){
  $sorted_tags = array();
  foreach($tags as $tag){
    $tag_name = $tag->name;
    $first_char = mb_substr($tag_name, 0, 1);
    $short_pinyin = Pinyin::getShortPinyin($first_char);
    $sorted_tags[$short_pinyin][] = $tag_name;
  }
  ksort($sorted_tags);
  return $sorted_tags;
}
?>
