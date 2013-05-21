<?php

/**
* Gets last $number_of_subpages from their $parent_page
* If the <!--more--> tag is ignored ($ignore_more=true) returns the entire content of the subpages
*
* @param mixed $parent_page Contains either the slug of the parent page or it's ID
* @param integer $number_of_subpages Number of subpages to return
* @param boolean $ignore_more Whether to ignore the <!--more--> tag or not
* @return array Contents and titles of subapages
*/
function wpascms_get_subpages($parent_page='portfolio', $number_of_subpages=2, $ignore_more=false)
{
    global $wpdb;

    if(is_string($parent_page))
    {
        $parent_page_ID = wpascms_get_parent_page_ID($parent_page);
    }
    else
    {
        $parent_page_ID = $parent_page;
    }  

    if($number_of_subpages == 0)
    {
        $limit = '';
    }
    else
    {
        $limit = 'LIMIT 0, ' . $number_of_subpages;
    }

    // Get all subpages that are published and are childs of the given parent page
    // and order them by date in descending order (latest first)
    // also, if needed, limit to the latest $number_of_subpages
    $subpages = $wpdb->get_results("SELECT * FROM $wpdb->posts
                                    WHERE `post_parent` = '$parent_page_ID' AND `post_type` = 'page' AND `post_status` = 'publish'
                                    ORDER BY `post_date` DESC $limit");

    if(!$ignore_more)
    {
        foreach($subpages as $key=>$subpage)
        {
            if(strpos($subpage->post_content, '<!--more-->') !== false)
            {
                $short_content = explode('<!--more-->', $subpage->post_content, 2);
                $subpages[$key]->post_content = $short_content[0] . '<a href="' . get_permalink($subpage->ID) . '">Read more...</a>';
            }
        }
    }

    return $subpages;
}

function wpascms_get_parent_page_ID($parent_page)
{
    global $wpdb;

    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE `post_name` = %s AND `post_type` = 'page' AND `post_status` = 'publish'", $parent_page));

    return $id;
}

?>
