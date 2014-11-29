<?php

namespace Vend\PheatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ManagementController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('VendPheatBundle:Management:index.html.twig', [

        ]);
    }
}
