<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Child_Public_Ajax
{
    protected string $method;
    private object $responseJson;

    /**
     * The AJAX DATA
     *
     * @access   private
     * @var      array|object $data The AJAX DATA.
     */
    protected $data;

    /**
     * Store plugin main class to allow child access.
     *
     * @var Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    protected Register_Child_Hooks $main;
    private static $child_ajax_instance;

    /**
     * @return static
     */
    public static function child_ajax_instance(Register_Child_Hooks $main, Environment $twig): self
    {
        if (is_null(self::$child_ajax_instance)) {
            self::$child_ajax_instance = new self($main, $twig);
        }
        return self::$child_ajax_instance;
    }

    public function __construct(Register_Child_Hooks $main, Environment $twig)
    {
        $this->main = $main;
        $this->twig = $twig;
        $this->method = filter_input(INPUT_POST, 'method', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
        $this->responseJson = (object)['status' => false, 'msg' => date('H:i:s'), 'type' => $this->method];
    }

    /**
     * PUBLIC AJAX RESPONSE.
     * @throws Exception
     */
    public function public_ajax_handle(): object
    {
        if (!method_exists($this, $this->method)) {
            throw new Exception("Method not found!#Not Found");
        }
        return call_user_func_array(self::class . '::' . $this->method, []);
    }

    private function ajax_test():object
    {
        $this->responseJson->status = true;
        return  $this->responseJson;
    }
}