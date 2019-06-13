<?php
namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostController extends Controller
{
	/**
	 * @Route("/", name="index")
	 * @Method({"GET", "POST"})
	 */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('posts/index.html.twig', array('posts' => $posts));
    }

    /**
     * @Route("/posts/{id}")
     * @Method({"GET"})
     */
    public function show($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        
        return $this->render('posts/show.html.twig', array('post' => $post ));
    }

    /**
     * @Route("/posts")
     * @Method({"GET", "POST"})
     */
    public function store(Request $request)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')
            ))
            ->add('description', TextareaType::class, array(
                'attr' =>
                array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-info btn-block mt-3')
            ))->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('posts/create.html.twig', array('form' => $form->createView() ));
    }

    /**
     * @Route("/posts/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $post = new Post();
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')
            ))
            ->add('description', TextareaType::class, array(
                'attr' =>
                array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-info btn-block mt-3')
            ))->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('posts/edit.html.twig', array('form' => $form->createView() ));
    }

    /**
     * @Route("/posts/{id}/destroy")
     * @Method({"DELETE"})
     */
    public function destroy(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}