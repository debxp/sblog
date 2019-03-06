<?php

// SETTINGS --------------------------------------------------------------------

$simple_blog_title   = 'Sblog';
$simple_blog_tagline = 'some great blog tagline!';
$simple_blog_url     = 'http://localhost/blau/sblog/'; 
$simple_blog_id      = 'znld8z';
$simple_blog_theme   = 'default';
$simple_blog_favicon = 'favicon.png';
$simple_blog_lang    = 'pt-br';

// MAIN MENU -------------------------------------------------------------------

// You can add pages to your blog by following this model:
//
//    'menu label' => 'note id',
// 
// Don't change the note id 'home'!

$simple_blog_menu = [
    'Home'          => 'home',
    'Sample Page 1' => 'Simplenote note id used as page',
    'Sample Page 2' => 'Another Simplenote page id used as page',
];

// TEMPLATES -------------------------------------------------------------------

// You can edit and/or use these templates...

$sb_header = '<!DOCTYPE html>
<html lang="%BLOG_LANG%">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%BLOG_TITLE%</title>
    <link rel="icon" type="image/png" href="%BLOG_FAVICON%" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Quicksand:300,400|Ubuntu+Mono" rel="stylesheet">
    <link href="sb/themes/%BLOG_THEME%/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
';

$sb_titlebar = '
    <h1><a href="%BLOG_URL%">%BLOG_TITLE%</a></h1>
    <p>%BLOG_TAGLINE%</p>
';

$sb_footer = '
</body>
</html>
';

// FUNCTIONS -------------------------------------------------------------------

function note_get_contents($note_page_id) {
    $h = curl_init();
    
    curl_setopt($h, CURLOPT_TIMEOUT, 15);
    curl_setopt($h, CURLOPT_URL, "https://app.simplenote.com/p/".$note_page_id);
    curl_setopt($h, CURLOPT_RETURNTRANSFER, true);
    
    if ( false === ($r = curl_exec($h))) {
        error_log(curl_error($h));
    } else {
        return $r;
    }
}

function simple_posts_data() {
    global $simple_blog_id;
    
    $posts = array();
    
    $search = "/\<div class=\"note note\-detail\-markdown\"\>(.*?)\<\/div\>\<\!-- \.note --\>/si";
    
    preg_match($search, note_get_contents($simple_blog_id), $m);
    preg_match_all("/\<ul\>(.*?)\<\/ul\>/si", $m[1], $list);
    
    foreach ($list[1] as $i) {
        $posts[] = explode("\n", trim(str_replace(['<li>', '</li>'], "", $i)));
    }
    
    return array_reverse($posts);
}

function simple_post_page() {
    $post_id = get_current_page();
    
    $cont_search = "/\<div class=\"note(.*?)\<\!-- \.note --\>/si";
    preg_match($cont_search, note_get_contents($post_id), $m);
    
    $cont = $m[0];
    $cont = preg_replace('/\<p\>\^(.*?)\^\<\/p\>/','<p class="post-info">\1</p>',$cont);
    $cont = preg_replace('/\<pre\>\<code\>(.*?)\<\/code\>\<\/pre\>/si','<pre class="code">\1</pre>',$cont);
    $cont = preg_replace('/\<blockquote\>.*?\<p\>(.*?)\<\/p\>.*?\<\/blockquote\>/si','<pre class="wrap">\1</pre>',$cont);
    
    echo $cont."\n";
}

function simple_blog_page() {
    global $simple_blog_url;
    
    $spd = simple_posts_data();
    $url = rtrim($simple_blog_url,'/').'/?p=';

    $posts = "\n";
    foreach ($spd as $post) {
        $p  = '<div class="posts">'."\n";
        $p .= '<h1><a href="'.$url.$post[3].'">'.$post[0]."</a></h1>\n";
        $p .= "<p class=\"post-info\">".$post[2]." - por ".$post[1]."</p>\n";
        $p .= "<p>".$post[4]."</p>\n";
        $p .= "</div>\n\n";
        
        $posts .= $p;
    }
    echo $posts;
}

function sblog_header() {
    global $sb_header;
    global $simple_blog_title;
    global $simple_blog_tagline;
    global $simple_blog_theme;
    global $simple_blog_favicon;
    global $simple_blog_lang;
    global $simple_posts_data;
    
    if (get_current_page() == 'home') {
        $title = $simple_blog_title." - ".$simple_blog_tagline;
    } else {
        $title = $simple_posts_data[0]." - ".$simple_blog_title;
    }
    
    $sr = [
        '%BLOG_LANG%'         => $simple_blog_lang,
        '%BLOG_THEME%'        => $simple_blog_theme,
        '%BLOG_FAVICON%'      => $simple_blog_favicon,
        '%BLOG_TITLE%'        => $title
    ];
    
    echo str_replace(array_keys($sr), array_values($sr), $sb_header);
}

function sb_titlebar() {
    global $sb_titlebar;
    global $simple_blog_url;
    global $simple_blog_title;
    global $simple_blog_tagline;
    
    $sr = [
        '%BLOG_URL%'     => $simple_blog_url,
        '%BLOG_TITLE%'   => $simple_blog_title,
        '%BLOG_TAGLINE%' => $simple_blog_tagline
    ];
    
    echo str_replace(array_keys($sr), array_values($sr), $sb_titlebar);
}

function sb_menu() {
    global $simple_blog_url;
    global $simple_blog_menu;
    
    $m = "<ul>\n"; 
    foreach ($simple_blog_menu as $k => $i) {
        if ($i == 'home') {
            $url = rtrim($simple_blog_url, '/').'/';
        } else {
            $url = rtrim($simple_blog_url, '/').'/?p='.$i;
        }
        if (get_current_page() == $i) {
            $m .= '<li class="current">'.$k."</li>\n";
        } else {
            $m .= '<li><a href="'.$url.'">'.$k."</a></li>\n";
        }
    }
    $m .= "</ul>\n";
    
    echo $m;
}

function sblog_footer() {
    global $sb_footer;
    echo $sb_footer;
}

function get_current_page() {
    if (isset($_GET['p'])) {
        return $_GET['p'];
    } else {
        return 'home';
    }
}

function sblog() {
    if (get_current_page() == 'home') {
        echo "<div class=\"posts-container\">\n";
        simple_blog_page(); 
        echo "</div><!-- .posts-container -->\n";
    } else {
        simple_post_page();
    }
}
