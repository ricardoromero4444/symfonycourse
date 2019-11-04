<?php

namespace App\Controller;

use App\Entity\Post;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/post", name="post.")
     */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create()
    {
    	$post = new Post();

    	$post->setTitle('New title');
    	
    	// Entity manager
    	$em = $this->getDoctrine()->getManager();

    	// Simply creates a query
    	$em->persist($post);
    	// Actually performs the query
    	$em->flush();

    	return new Response('Post was created. ');

    }
}
