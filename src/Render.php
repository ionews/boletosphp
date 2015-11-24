<?php
namespace Boletos;

class Render
{
    private $data = array();
    private $render = false;

    public function __construct($template, array $variables = [])
    {
        try {
            $file = __DIR__ . '/templates/' . strtolower($template) . '.php';

            foreach ($variables as $variable => $value) {
                $this->assign($variable, $value);
            }

            if (file_exists($file)) {
                $this->render = $file;
            } else {
                $this->render = __DIR__ . '/templates/default.php';
            }
        } catch (customException $e) {
            echo $e->errorMessage();
        }
    }

    public function assign($variable, $value)
    {
        $this->data[$variable] = $value;
    }

    public function html()
    {
        extract($this->data);

        ob_start();
        include $this->render;
        $renderedView = ob_get_clean();

        return $renderedView;
    }
}
