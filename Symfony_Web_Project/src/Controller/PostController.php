<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
//use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{

    /**
     * @Route("/post", name="post")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);

    }


    /**
     * @Route("/post/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {

        $post = new Post(); // New object

        $post->setWorkhour("08:00 to 22:00"); // All cleaner's workhour is same

        $post->setAvailable('Available');

        $post->setWorkdays('Monday, Tuesday, Wednesday, Thursday, Saturday, Sunday');

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {

            //Access to Database
            $em = $this->getDoctrine()->getManager();
            dump($post);
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post'));

        }

        //Return to create page again (if not submitted any new cleaner input)
        return $this->render('post/create.html.twig',
            ['form' => $form->createView()]);

    }


    /**
     * @Route("/show/{id}", name="post.show")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function show(Post $posts) // Show All Info About One of the Cleaners
    {

        //create show view
        return $this->render('post/show.html.twig', [
            'post' => $posts
        ]);

    }


    /**
     * @Route ("/delete/{id}", name="post.delete")
     */
    public function remove(Post $post) // Function to Remove a Cleaner From Database
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'The Cleaner has been removed from the database');

        return $this->redirect($this->generateUrl('post'));

    }


    /**
     * @Route("/post/createDatabase", name="createalldatabase")
     * @param Request $request
     * @return Response
     */
    public function createAllDatabase() // This function will create all database automatic
    {
        $names = array('John', 'Bruce', 'Paul', 'Dave', 'Stipe', 'Jennifer', 'Daisy', 'Gabriella');
        $companies = array('Apple', 'Apple', 'Apple', 'Samsung', 'Samsung', 'Samsung', 'Xiaomi', 'Huawei');

        for($i = 0; $i <= 7; $i++)
        {
            $post = new Post();

            $post->setName($names[$i]);
            $post->setAvailable('Available');
            $post->setWorkhour('08:00 to 22:00');
            $post->setCompany($companies[$i]);
            $post->setWorkdays('Monday, Tuesday, Wednesday, Thursday, Saturday, Sunday');

            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('post'));
    }


    /**
     * @Route("/assignment", name="giveassignment")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function Assignment(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('post/assignment.html.twig', [
            'posts' => $posts,
        ]);

    }



    /**
     * @Route("/assignmentCompanyInfo/{id}", name="giveassignmentinfocompany")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function giveAssignmentCompanyRoute(Post $post,PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('post/assignmentCompany.html.twig', [
            'posts' => $posts,
            'ShowedCompanyName' => $post->getCompany()
        ]);

    }



    /**
     * @Route("/assignmentCompInfo1/{id}", name="assignmentCompanyInfoTwoHours")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function assignmentCompanyInfoTwoHours(Post $post, PostRepository $postRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $posts = $postRepository->findAll();

        if($this->availableControl($post)) //if the availability is true, yes or available turn it to no
        {

            $bookingText = 'asdasdasdasdas';

            $post->setAvailable('Not Available');
            $post->setWorkhour($this->workhourConfiguratorFor2Hours($post, 2));


            $post->setBooking($post->getName() . ' assigned to work on ' . $post->getCompany() . ' for 2 hours.');

            $this->addFlash('success', ' Cleaner Status Updated !  2 Hours Work Time');

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $posts = $postRepository->findAll();

        }
        else
        {

            $this->addFlash('success', ' Cleaner is not Available Now !');

        }

        return $this->render('post/assignmentCompany.html.twig', [
            'posts' => $posts,
            'ShowedCompanyName' => $post->getCompany()
        ]);
    }


    /**
     * @Route("/assignmentCompInfo2/{id}", name="assignmentCompanyInfoFourHours")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function assignmentCompanyInfoFourHours(Post $post, PostRepository $postRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $posts = $postRepository->findAll();

        if($this->availableControl($post)) //If the availability is true, yes or available -> turn it to no
        {

            $post->setAvailable('Not Available');
            $post->setWorkhour($this->workhourConfiguratorFor2Hours($post, 4));

            $post->setBooking($post->getName() . ' assigned to work on ' . $post->getCompany() . ' for 4 hours.');

            $this->addFlash('success', ' Cleaner Status Updated ! 4 Hours Work Time');

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $posts = $postRepository->findAll();

        }
        else
        {
            $this->addFlash('success', ' Clenaer is not Available Now !');
        }

        return $this->render('post/assignmentCompany.html.twig', [
            'posts' => $posts,
            'ShowedCompanyName' => $post->getCompany()

        ]);

    }


    //WorkHour Configurator to adjust Work Hours
    public function workhourConfiguratorFor2Hours(Post $post, $workHour) //$workHour -> (2 or 4)
    {

        if($workHour == 2)
        {

            if($post->getWorkhour() == '08:00 to 22:00')
            {
                return '10:00 to 22:00';
            }
            if($post->getWorkhour() == '10:00 to 22:00')
            {
                return '12:00 to 22:00';
            }
            else if($post->getWorkhour() == '12:00 to 22:00')
            {
                return '14:00 to 22:00';
            }
            else if($post->getWorkhour() == '14:00 to 22:00')
            {
                return '16:00 to 22:00';
            }
            else if($post->getWorkhour() == '16:00 to 22:00')
            {
                return '18:00 to 22:00';
            }
            else if($post->getWorkhour() == '18:00 to 22:00')
            {
                return '20:00 to 22:00';
            }
            else if($post->getWorkhour() == '20:00 to 22:00')
            {
                return '22:00 to 22:00';
            }

        }
        else if($workHour == 4)
        {
            if($post->getWorkhour() == '08:00 to 22:00')
            {
                return '12:00 to 22:00';
            }
            else if($post->getWorkhour() == '10:00 to 22:00')
            {
                return '14:00 to 22:00';
            }
            else if($post->getWorkhour() == '12:00 to 22:00')
            {
                return '16:00 to 22:00';
            }
            else if($post->getWorkhour() == '14:00 to 22:00')
            {
                return '18:00 to 22:00';
            }
            else if($post->getWorkhour() == '16:00 to 22:00')
            {
                return '20:00 to 22:00';
            }
            else if($post->getWorkhour() == '18:00 to 22:00')
            {
                return '22:00 to 22:00';
            }

        }

        return 'Workhour Error';

    }


    // Availability Control of Cleaner
    public function availableControl($post)
    {

        if($post->getAvailable() == 'Available')
        {
            return true;
        }
        else
            return false;

    }


}