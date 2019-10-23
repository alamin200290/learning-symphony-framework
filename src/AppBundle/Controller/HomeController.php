<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="homepage")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();

    	if( $session->get('userName') != ""){
    		
    		$data = array('uname'=> $session->get('userName'));
	        return $this->render('home/index.html.twig', $data);
    	
    	}else{
    		//$data = array('status'=> "invalid request, login first!");
	        return $this->redirect('/login');
    	}
    }


    /**
     * @Route("/home/profile", name="profilepage")
     */
    public function profile(Request $request)
    {          
        $session = $request->getSession();
        if( $session->get('userName') != ""){
        
            $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(User::class)
                        ->find($session->get('uid'));

            return $this->render('home/profile.html.twig', ['user'=> $user]);
        
        }else{
            return $this->redirect('/login');
        }
    }

    /**
     * @Route("/home/adduser", name="adduserpage")
     */
    public function adduser(Request $request)
    {          
        $session = $request->getSession();
        if( $session->get('userName') != ""){
            if($request->getMethod() == "POST"){

                $user = new User();
                $user->setUname($request->get('uname'));
                $user->setPassword($request->get('password'));
                $user->setType($request->get('type'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                
                return $this->redirect('/home/userlist');

            }else{
                return $this->render('home/adduser.html.twig');
            }
        }else{
            return $this->redirect('/login');
        }
    }

    /**
     * @Route("/home/userlist", name="userlistpage")
     */
    public function userlist(Request $request)
    {          
        $session = $request->getSession();
        if($session->get('userName') != ""){
            
            $users = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(User::class)
                        ->findAll();

            return $this->render('home/userlist.html.twig', ['user'=> $users]);
            //return print_r($users);
        }else{
            return $this->redirect('/login');
        }
    }

    /**
     * @Route("/home/edit/{uid}", name="edituserpage")
     */
    public function edituser(Request $request, $uid)
    {          
        $session = $request->getSession();
        if( $session->get('userName') != "")
        {
            if($request->getMethod() == "POST")
            {

                $entityManager = $this->getDoctrine()->getManager();
                $user = $entityManager->getRepository(User::class)->find($uid);
                $user->setUname($request->get('uname'));
                $user->setPassword($request->get('password'));
                $user->setType($request->get('type'));
                $entityManager->flush();
                
                return $this->redirect('/home/userlist');

            }else{

                $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(User::class)
                        ->find($uid);

                return $this->render('home/edit.html.twig', ['user'=> $user]);
            }
        }else{
            return $this->redirect('/login');
        }
    }

    /**
     * @Route("/home/delete/{uid}", name="deleteuserpage")
     */
    public function deleteuser(Request $request, $uid)
    {          
        $session = $request->getSession();
        if( $session->get('userName') != "")
        {
            if($request->getMethod() == "POST")
            {
                $entityManager = $this->getDoctrine()->getManager();
                $user = $entityManager->getRepository(User::class)->find($uid);
                $entityManager->remove($user);
                $entityManager->flush();
                
                return $this->redirect('/home/userlist');

            }else{

                $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(User::class)
                        ->find($uid);

                return $this->render('home/delete.html.twig', ['user'=> $user]);
            }
        }else{
            return $this->redirect('/login');
        }
    }
}
