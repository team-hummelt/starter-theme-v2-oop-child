<?php

class Team_Rest_Endpoint
{

    protected Register_Child_Hooks $main;

    public function __construct(Register_Child_Hooks $main)
    {
        $this->main = $main;
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_block_child_routes()
    {
        $version = '1';
        $namespace = 'child-methods/v' . $version;
        $base = '/';

        @register_rest_route(
            $namespace,
            $base . 'get-items/(?P<method>[\S]+)',

            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'ulrichshaus_members_endpoint_get_response'),
                'permission_callback' => array($this, 'permissions_check')
            )
        );
    }

    /**
     * Get one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function ulrichshaus_members_endpoint_get_response(WP_REST_Request $request)
    {

        $method = (string)$request->get_param('method');
        if (!$method) {
            return new WP_Error(404, ' Method failed');
        }

        return $this->get_method_item($method);

    }

    /**
     * GET Post Meta BY ID AND Field
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_method_item($method)
    {
        if (!$method) {
            return new WP_Error(404, ' Method failed');
        }
        $tempArr = [];
        $response = new stdClass();
        switch ($method) {
            case 'get-ulrichshaus-members-block':
            case 'get-ulrichshaus-leistungen-block':
                $templates = [
                    '0' => [
                        'id' => 1,
                        'name' => 'Standard Template'
                    ]
                ];

                if($templates){
                    foreach ($templates as $tmp) {
                        $temp_item = [
                            'id' => $tmp['id'],
                            'name' => $tmp['name']
                        ];
                        $tempArr[] = $temp_item;
                    }
                }


            $catArr = [];
            $method == 'get-ulrichshaus-leistungen-block' ? $taxonomy = 'leistungen_category' : $taxonomy = 'team_category';
            $terms = $this->child_theme_get_custom_terms($taxonomy);
                if($terms->status){
                    foreach ($terms->terms as $tmp){
                        $cat_item = [
                            'id' => $tmp->term_id,
                            'name' => $tmp->name
                        ];
                        $catArr[] = $cat_item;
                    }
                }

                $response->templates = $tempArr;
                $response->categories = $catArr;
                break;


        }
        return new WP_REST_Response($response, 200);
    }

    /**
     * Get a collection of items.
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return void
     */
    public function get_items(WP_REST_Request $request)
    {


    }

    /**
     * @param string $taxonomy
     * @return object
     */
    public function child_theme_get_custom_terms(string $taxonomy = 'team_category'): object
    {
        $return = new  stdClass();
        $return->status = false;
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'parent' => 0,
            'hide_empty' => false,
        ));

        if (!$terms) {
            return $return;
        }
        $return->status = true;
        $return->terms = $terms;
        return $return;
    }

    /**
     * Check if a given request has access.
     *
     * @return bool
     */
    public function permissions_check(): bool
    {
        return current_user_can('edit_posts');
    }
}