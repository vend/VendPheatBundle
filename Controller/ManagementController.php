<?php

namespace Vend\PheatBundle\Controller;

use Pheat\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ManagementController extends Controller
{
    public function indexAction(Request $request)
    {
        $manager = $this->get('pheat.manager');



        return $this->render('VendPheatBundle:Management:index.html.twig', [
            'providers' => $manager->getProviders(),
            'features'  => $manager->getFeatureSet()
        ]);
    }
}
