<?php

use Twig\Environment;

class Child_Public_Ajax
{
    protected string $method;

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
    private string $img_size = 'full';

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

        $this->method = '';
        if (isset($_POST['daten'])) {
            $this->data = $_POST['daten'];
            $this->method = filter_var($this->data['method'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
        }

        if (!$this->method) {
            $this->method = $_POST['method'];
        }
    }


    /**
     * PUBLIC AJAX RESPONSE.
     */
    public function public_ajax_handle(): object
    {
        $responseJson = new stdClass();
        $responseJson->status = false;
        $responseJson->msg = date('H:i:s', current_time('timestamp'));
        switch ($this->method) {
            case 'ajax-test':
                $responseJson->status = true;
                break;
        }
        return $responseJson;
    }
}