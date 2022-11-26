<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Team_Twig_Templates
{
    /**
     * Store plugin main class to allow public access.
     *
     * @var Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    protected Register_Child_Hooks $main;
    private string $img_size = 'full';

    public function __construct(Register_Child_Hooks $main, Environment $twig)
    {
        $this->main = $main;
        $this->twig = $twig;

    }

    public function filter_ulrichshaus_get_template($attributes): string
    {
        isset($attributes['selectedCategory']) && $attributes['selectedCategory'] ? $catId = (int) $attributes['selectedCategory'] : $catId = 0;
        isset($attributes['selectedTemplate']) && $attributes['selectedTemplate'] ? $templateId = (int) $attributes['selectedTemplate'] : $templateId = 1;
        isset($attributes['handle']) && $attributes['handle'] ? $handle = $attributes['handle'] : $handle = 'desktop';
        isset($attributes['page']) && $attributes['page'] ? $page = (int) $attributes['page'] : $page = 1;
        isset($attributes['first']) && $attributes['first'] ? $first = (int) $attributes['first'] : $first = 0;

        $arr = [];
        $handle == 'desktop' ? $numberPost = -1 : $numberPost = NUMBER_POST_MOBIL_TEAM;
        $handle == 'mobil' ? $offset = ($page - 1) * NUMBER_POST_MOBIL_TEAM : $offset = 0;

        $args = $this->get_custom_posts('team_category', $catId, 'team', $numberPost, $offset);

        $posts = get_posts($args);
        foreach ($posts as $post) {
            $thumbId = get_post_thumbnail_id($post->ID);
            $items = [
                'post_id' => $post->ID,
                'meta' => $this->child_get_post_meta($post->ID),
                'post_content' => $post->post_content,
                'permalink' => get_the_permalink($post->ID),
                'post_title' => $post->post_title,
                'menu_order' => $post->menu_order,
                'title_img' => get_the_post_thumbnail_url($post->ID, $this->img_size),
                'wp_get_attachment_image_srcset' => wp_get_attachment_image_srcset($thumbId)
            ];

            $arr[] = $items;
        }

        $arr = $this->object2array_recursive($arr);

        $data = [
            'data' => $arr,
            'template' => 'slider-container',
            'templateId' => $templateId,
            'first' => $first,
            'catId' => $catId
        ];

        $temp = '';
        switch ($handle){
            case 'desktop':
                $temp = '@templates/template'.$templateId.'.twig';
                break;
            case'mobil':
                $args = $this->get_custom_posts('team_category', $catId, 'team', -1, 0);
                $posts = get_posts($args);
                $total = count($posts);
                $last = ceil($total / NUMBER_POST_MOBIL_TEAM);
                $last > $page ? $next = false : $next = true;
                $data['next'] = $next;
                $data['page'] = $page ;
                $data['last'] = $last;
                $temp = '@loops/team'.$templateId.'Mobil.twig';
                break;
        }

        try {
            $template = $this->twig->render($temp, $data);
            $template = $this->html_compress_template($template);
        } catch (LoaderError|SyntaxError|RuntimeError|Throwable $e) {
            return '';
        }

        return $template;
    }

    public function filter_ulrichshaus_get_leistungen_template($attributes) : string
    {
        isset($attributes['selectedCategory']) && $attributes['selectedCategory'] ? $catId = (int) $attributes['selectedCategory'] : $catId = 0;
        isset($attributes['selectedTemplate']) && $attributes['selectedTemplate'] ? $templateId = (int) $attributes['selectedTemplate'] : $templateId = 1;
        isset($attributes['page']) && $attributes['page'] ? $page = (int) $attributes['page'] : $page = 1;
        isset($attributes['handle']) && $attributes['handle'] ? $handle = $attributes['handle'] : $handle = 'desktop';
        isset($attributes['first']) && $attributes['first'] ? $first = (int) $attributes['first'] : $first = 0;
        $arr = [];
        $handle == 'desktop' ? $numberPost = -1 : $numberPost = NUMBER_POST_MOBIL_LEISTUNGEN;
        $args = $this->get_custom_posts('leistungen_category', $catId, 'leistungen', $numberPost);
        $posts = get_posts($args);
        foreach ($posts as $post) {
            $thumbId = get_post_thumbnail_id($post->ID);
            $items = [
                'post_id' => $post->ID,
                'post_content' => $post->post_content,
                'permalink' => get_the_permalink($post->ID),
                'post_title' => $post->post_title,
                'menu_order' => $post->menu_order,
                'title_img' => get_the_post_thumbnail_url($post->ID, 'full'),
                'wp_get_attachment_image_srcset' => wp_get_attachment_image_srcset($thumbId)
            ];

            $arr[] = $items;
        }

        $arr = $this->object2array_recursive($arr);

        $data = [
            'data' => $arr,
            'templateId' => $templateId,
            'first' => $first,
            'catId' => $catId
        ];

        $temp = '';
        switch ($handle){
            case 'desktop':
                $temp = '@templates/leistungen'.$templateId.'.twig';
                break;
            case'mobil':
                $args = $this->get_custom_posts('leistungen_category', $catId, 'leistungen', -1, 0);
                $posts = get_posts($args);
                $total = count($posts);
                $last = ceil($total / NUMBER_POST_MOBIL_TEAM);
                $last > $page ? $next = true : $next = false;
                $data['next'] = $next;
                $data['page'] = $page ;
                $data['last'] = $last;
                $temp = '@loops/leistungen'.$templateId.'Mobil.twig';
                break;
        }
        try {
            $template = $this->twig->render($temp, $data);
            $template = $this->html_compress_template($template);
        } catch (LoaderError|SyntaxError|RuntimeError|Throwable $e) {
            return '';
        }

        return $template;
    }

    public function child_get_post_meta($postId):object
    {
        $return = new stdClass();
        $return->name = get_post_meta($postId, '_team_name', true);
        $return->position = get_post_meta($postId, '_team_position', true);
        $return->arbeitsgebiet1 = get_post_meta($postId, '_team_arbeitsgebiet1', true);
        $return->arbeitsgebiet2 = get_post_meta($postId, '_team_arbeitsgebiet2', true);
        $return->arbeitsgebiet3 = get_post_meta($postId, '_team_arbeitsgebiet3', true);
        $return->arbeitsgebiet4 = get_post_meta($postId, '_team_arbeitsgebiet4', true);
        $return->arbeitsgebiet5 = get_post_meta($postId, '_team_arbeitsgebiet5', true);
        $return->arbeitsgebiet6 = get_post_meta($postId, '_team_arbeitsgebiet6', true);
        $return->lebenslauf = get_post_meta($postId, '_team_lebenslauf', true);
        return $return;
    }


    public function get_custom_posts($taxonomie, $catId, $postType, $numberposts, $offset = 0): array
    {
        if($catId){
            $args = array(
                'post_type' => $postType,
                'offset' => $offset,
                'numberposts' => $numberposts,
                'post_status'    => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomie,
                        'field'    => 'term_id',
                        'terms'    => $catId,
                    ),
                ),
            );
        } else {
            $args = array(
                'post_type' => $postType,
                'offset' => $offset,
                'numberposts' => $numberposts,
                'post_status'    => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
            );
        }

        return $args;

    }

    private function html_compress_template(string $string): string
    {
        if (!$string) {
            return $string;
        }
        return preg_replace(['/<!--(.*)-->/Uis', "/[[:blank:]]+/"], ['', ' '], str_replace(["\n", "\r", "\t"], '', $string));
    }

    private function object2array_recursive($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * @param $array
     *
     * @return object
     */
    private function hupaArrayToObject($array): object
    {
        foreach ($array as $key => $value)
            if (is_array($value)) $array[$key] = self::hupaArrayToObject($value);
        return (object)$array;
    }
}