<?php

namespace BlogmdBundle\Controller;

use BlogmdBundle\Entity\Post;
use BlogmdBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 * @package BlogmdBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/blog", name="blog-list")
     */
    public function listAction()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('@App/admin/list.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/new", name="blog-new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        if (!$post->getContent()) {
            // Set a default content cause we have a strange behavior when trying to save empty textareas
            // This is caused by CodeMirror.
            $post->setContent('Your post here...');
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post created successfully.');

            return $this->redirectToRoute('blog-list');
        }

        return $this->render(
            '@App/admin/form.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="blog-edit")
     *
     * @param Post    $post
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post modified successfully.');

            return $this->redirectToRoute('blog-list');
        }

        return $this->render(
            '@App/admin/form.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="blog-delete")
     *
     * @param Post    $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Post $post)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post has been deleted.');

        return $this->redirectToRoute('blog-list');
    }

    /**
     * @Route("/blog/{id}", name="blog-item")
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Post $post)
    {
        return $this->render('@App/admin/item.html.twig', ['post' => $post]);
    }
}
