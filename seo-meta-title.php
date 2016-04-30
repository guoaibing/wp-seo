<?php

add_filter('wp_title','seo_title_filter',100);
function seo_title_filter($title){
    global $page,$paged;
    $split = get_option('seo_split');
    $title = trim($title);

    // 首页标题优化
    if((is_home() || is_front_page())){
        if(get_option('seo_title'))
            $title = get_option('seo_title');
        else
            $title = get_bloginfo('name').$split.get_bloginfo('description');
    }
    // 分类页标题
    elseif(is_category()){
        global $cat;
        $cat_id = is_object($cat) ? $cat->cat_ID : $cat;
        $cat_title = single_cat_title('',false);
        $cat_seo_title = get_term_meta($cat_id,'seo_title',true);
        $title = $cat_seo_title ? $cat_seo_title : $cat_title;
        $title .= $split.get_bloginfo('name');
    }
    // 标签页标题
    elseif(is_tag()){
        global $wp_query;
        $tag_id = $wp_query->queried_object->term_id;
        $tag_name = $wp_query->queried_object->name;
        $tag_seo_title = get_term_meta($tag_id,'seo_title',true);
        $title = $tag_seo_title ? $tag_seo_title : $tag_name;
        $title .= $split.get_bloginfo('name');
    }
    // 文章页的标题
    elseif(is_singular()){
        global $post;
        $title = trim($post->post_title) ? $post->post_title : $post->post_date;
        $title .= $split.get_bloginfo('name');
    }
    elseif(is_feed()){
        return $title;
    }
    // 其他情况
    else{
        $title .= $split.get_bloginfo('name');
    }
    if($paged >= 2 || $page >= 2){
        $title .= $split.sprintf(__('第%s页'),max($paged,$page));
    }
    $title = seo_clear_code($title);
    return $title;
}