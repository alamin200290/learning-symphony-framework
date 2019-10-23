<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class LoginController extends Controller
{
	/**
     * @Route("/", name="indexpage")
     */
    public function landingPage(Request $request)
    {
    	return $this->redirect('/login');
    }

    /**
     * @Route("/login", name="loginpage")
     */
    public function index(Request $request)
    {
    	if($request->getMethod() == "POST"){
    		
            $uname      = $request->get('username');
            $password   = $request->get('password');
            
            $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(User::class)
                        ->findBy(
                            array('uname' => $uname, 'password' => $password)
                        );

            if(!is_null($user))
            {
                $session = $request->getSession();
                $session->set('userName', $user[0]->getUname());
                $session->set('uid', $user[0]->getId());
                return $this->redirect('/home');

            }else{

                $data = array('status'=> 'invalid user!');
                return $this->render('login/index.html.twig', $data);
            }
    	
    	}else{            
    		$data = array('status'=> '');
	        return $this->render('login/index.html.twig', $data);
    	}
    }
}
