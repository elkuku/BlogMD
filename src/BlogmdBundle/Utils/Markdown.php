<?php

namespace BlogmdBundle\Utils;

/**
 * This class is a light interface between an external Markdown parser library
 * and the application. It's generally recommended to create these light interfaces
 * to decouple your application from the implementation details of the third-party library.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Markdown
{
    /**
     * @var \Parsedown
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new \Parsedown();
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        return $this->parser->text($text);
    }
}
