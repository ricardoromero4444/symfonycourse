<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form;
use App\Form\PostType;
use App\Repository\PostRepository;

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
    public function index(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
    	$post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Entity manager
            $em = $this->getDoctrine()->getManager();

            $file = $request->files->get('post')['attachment'];

            if ($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );
                $post->setImage($filename);
            }

        	$em->persist($post);  // Simply creates a query
            $em->flush();  // Actually performs the query

            return $this->redirect($this->generateUrl('post.index'));
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id, PostRepository $postRepository)
    {
        $post = $postRepository->findPostWithCategory($id);
    
        return $this->render('post/show.html.twig', [
            'post' => $post[0],
        ]);

    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function remove(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);

        $em->flush();

        $this->addFlash('success', 'The post post was removed.');

        return $this->redirect($this->generateUrl('post.index'));
    }
}
