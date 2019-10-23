<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends Controller
{
	/**
     * @Route("/logout", name="logoutpage")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->set('userName', '');
    	return $this->redirect('/login');
    }
}
