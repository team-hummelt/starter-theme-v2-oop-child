<?php

namespace Child\Ajax;

/**
 * The admin-specific functionality of the theme.
 *
 * @link       https://wwdh.de
 */

defined('ABSPATH') or die();

use Exception;

use Hupa\StarterV2\Admin_Document_Upload;
use Hupa\StarterV2\ChildSettings;
use Register_Child_Hooks;
use stdClass;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use WP_Term_Query;

class Class_Admin_Ajax
{
    private static $admin_ajax_instance;
    private string $method;
    private object $responseJson;
    use ChildSettings;

    /**
     * Store plugin main class to allow child access.
     *
     * @var Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    protected Register_Child_Hooks $main;

    /**
     * @return static
     */
    public static function admin_ajax_instance(Register_Child_Hooks $main, Environment $twig): self
    {
        if (is_null(self::$admin_ajax_instance)) {
            self::$admin_ajax_instance = new self($main, $twig);
        }
        return self::$admin_ajax_instance;
    }

    public function __construct(Register_Child_Hooks $main, Environment $twig)
    {
        $this->main = $main;
        $this->twig = $twig;
        $this->method = filter_input(INPUT_POST, 'method', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
        $this->responseJson = (object)['status' => false, 'msg' => date('H:i:s'), 'type' => $this->method];
    }

    /**
     * @throws Exception
     */
    public function admin_ajax_handle()
    {
        if (!method_exists($this, $this->method)) {
            throw new Exception("Method not found!#Not Found");
        }
        return call_user_func_array(self::class . '::' . $this->method, []);
    }

    private function admin_test():object
    {
        $this->responseJson->msg = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
        $this->responseJson->status = true;
        return  $this->responseJson;
    }

    /**
     * @throws Exception
     */
    private function dokument_upload()
    {
       /* $ifUserUpload = apply_filters('child_check_user_capabilities', false);
        if (!$ifUserUpload) {
            return $this->responseJson;
        }*/
        $uploadClass = Admin_Document_Upload::instance($this->main);
        $upload = $uploadClass->document_upload();
        if (!$upload->status) {
            $this->responseJson->msg = $upload->msg;
            exit(json_encode($upload->msg));
        }

        /*$insert = apply_filters('set_document_import', $upload);
        if (!$insert->status) {
            if (is_file(CHILD_CUSTOM_UPLOAD_DIR . $upload->filename)) {
                unlink(CHILD_CUSTOM_UPLOAD_DIR . $upload->filename);
            }
            exit(json_encode($insert->msg));
            //return $this->responseJson;
        }*/
        $ext = preg_replace('/^.*\./', '', $upload->orginalName);
        $this->responseJson->ext = $ext;
        $this->responseJson->size = apply_filters('get_document_size', $upload->size);
        $this->responseJson->type = $upload->type;
        //$this->responseJson->id = $insert->id;
        $this->responseJson->id = uniqid();
        $this->responseJson->filename = $upload->orginalName;
        $this->responseJson->date = date('d.m.Y', current_time('timestamp'));
        $this->responseJson->time = date('H:i:s', current_time('timestamp'));
        $this->responseJson->status = $upload->status;
        $this->responseJson->msg = $upload->msg;
        $this->responseJson->file_url = CHILD_CUSTOM_UPLOAD_URL . $upload->filename;
        $this->responseJson->handle = $this->method;
        return $this->responseJson;
    }




    private function html_compress_template(string $string): string
    {
        if (!$string) {
            return $string;
        }
        return preg_replace(['/<!--(.*)-->/Uis', "/[[:blank:]]+/"], ['', ' '], str_replace(["\n", "\r", "\t"], '', $string));
    }
}
