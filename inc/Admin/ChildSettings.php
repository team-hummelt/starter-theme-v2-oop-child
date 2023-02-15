<?php
namespace Hupa\StarterV2;
defined('ABSPATH') or die();

trait ChildSettings {
    protected array $child_settings_defaults;
    protected string $upload_min_role = 'manage_options';
    protected string $download_min_role = 'read';
    protected string $settings_min_role = 'manage_options';

    protected function get_child_defaults($args = '', $cap = NULL):array
    {
         $this->child_settings_defaults = [
            'upload_settings' => [
                'upload_min_role' => $this->upload_min_role,
                'download_min_role' =>  $this->download_min_role,
                'settings_min_role' => $this->settings_min_role
            ],
            'select_user_role' => [
                "0" => [
                    'capabilities' => 'subscriber',
                    'value' => 'read',
                    'name' => __('Subscriber', 'bootscore')
                ],
                "1" => [
                    'capabilities' => 'contributor',
                    'value' => 'edit_posts',
                    'name' => __('Contributor', 'bootscore')
                ],
                "2" => [
                    'capabilities' => 'subscriber',
                    'value' => 'publish_posts',
                    'name' => __('Author', 'bootscore')
                ],
                "3" => [
                    'capabilities' => 'editor',
                    'value' => 'publish_pages',
                    'name' => __('Editor', 'bootscore')
                ],
                "4" => [
                    'capabilities' => 'administrator',
                    'value' => 'manage_options',
                    'name' => __('Administrator', 'bootscore')
                ],
            ],
         ];

         if($args) {
             if($cap){
                 $return = [];
                 foreach ($this->child_settings_defaults[$args] as $tmp) {
                     if($tmp['capabilities'] == $cap) {
                         $return = $tmp;
                     }
                 }
                 return $return;
             }
             return  $this->child_settings_defaults[$args];
         }

         return $this->child_settings_defaults;
    }
}