<?php

namespace PHPCodex\Framework\Services;

class View
{

    protected $parameters = [];

    protected $output = null;

    protected $content = null;

    /**
     * @param string $resource
     * @param array $parameters
     * @return bool|string
     *
     * Set our parameters and generate our output.
     */
    public function __construct(string $resource, array $parameters = [])
    {
        $this->parameters = $parameters;
        $this->generate($resource);
    }

    /**
     * @param string $fp
     *
     * Generate our output. This will validate any
     * array replacements we require.
     */
    public function generate(string $fp)
    {
        if ($this->content == null) {
            $fp = str_replace('.', '/', $fp);
            $this->content = file_get_contents('../resources/views/' . $fp . '.html');
        }

        /**
         * Replace our variables.
         */
        preg_match_all('/{{[\s+]\$([a-zA-Z0-9]+)(.*?)[\s+]}}/', $this->content, $matches);

        foreach ($matches[0] as $key => $replacement) {
            $replaceWith = $this->obtain($matches[1][$key], $matches[2][$key], 'variable');
            $this->content = str_replace($replacement, $replaceWith, $this->content);
        }

        /**
         * Replace our function names.
         */

        preg_match_all('/{{[\s+]([a-zA-Z0-9\(]+)(.*?)(\))[\s+]}}/', $this->content, $matches);

        foreach ($matches[0] as $key => $replacement) {
            $replaceWith = $this->obtain($matches[1][$key], $matches[2][$key], 'function');
            $this->content = str_replace($replacement, $replaceWith, $this->content);
        }

    }

    /**
     * @param $start
     * @param $depth
     * @param string $action
     * @return mixed
     *
     * This method will actually obtain the real data value
     * back to the consumer. Here we are evaluating the
     * incoming to see what we want to replace by.
     */
    public function obtain($start, $depth, $action = 'function')
    {
        if ($action == 'variable') {

            $data = $this->parameters;
            eval("\$value = \$data[" . $start . "]" . $depth . " ?? false;");

        } else {

            eval("\$value = " . $start . $depth . ") ?? false;");

        }
        return $value;
    }

    /**
     * @return mixed
     *
     * This will finally return the output.
     */
    public function get()
    {
        return $this->content;
    }
}