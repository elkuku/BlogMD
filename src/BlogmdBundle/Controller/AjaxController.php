<?php

namespace BlogmdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @Route("/preview", name="preview")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction(Request $request)
    {
        $response = [];
        $text = $request->request->get('text');

        if (!$text) {
            $response['data'] = 'Nothing to preview...';
        } else {
            $markdown = $this->get('markdown');

            $response['data'] = $markdown->toHtml($text);
        }

        return $this->json($response);
    }
}
