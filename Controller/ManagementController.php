<?php

namespace Vend\PheatBundle\Controller;

use Pheat\Feature\Feature;
use Pheat\Feature\FeatureInterface;
use Pheat\Manager;
use Pheat\Provider\NullProvider;
use Pheat\Provider\ProviderInterface;
use Pheat\Provider\WritableProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Vend\PheatBundle\Form\Type\FeatureType;

class ManagementController extends Controller
{
    /**
     * @return Manager
     */
    protected function getManager()
    {
        return $this->get('pheat.manager');
    }

    public function indexAction(Request $request)
    {
        $manager = $this->getManager();

        return $this->render('VendPheatBundle:Management:index.html.twig', [
            'providers' => $manager->getProviders(),
            'features'  => $manager->getFeatureSet()
        ]);
    }

    /**
     * POST action that receives requests to set feature configurations
     *
     * Needless to say, this isn't a route you want Joe Public getting their
     * hands on. Probably only for your site admins.
     *
     * The default route to this action is /features/{provider}/{name} - you
     * should firewall accordingly.
     *
     * @param Request $request
     */
    public function setAction(Request $request)
    {


        $manager  = $this->getManager();


        if (!$provider instanceof WritableProviderInterface) {
            throw new MethodNotAllowedException('Not a writable provider');
        }



        $form = $this->createForm(new FeatureType(), $feature);

    }

    /**
     * Component that displays and edits individual feature forms
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function featureAction(Request $request)
    {
        $manager = $this->getManager();

        $providerName = $request->get('provider');
        $name         = $request->get('name');
        $provider     = $manager->getProvider($providerName);

        if (!$provider) {
            throw new NotFoundHttpException('Unknown provider');
        }

        if (!$provider instanceof ProviderInterface) {
            throw new \InvalidArgumentException('Expected ' . ProviderInterface::class);
        }

        $writable = $provider instanceof WritableProviderInterface;
        $feature  = $manager->getFeatureSet()->getFeatureFromProvider($name, $provider);

        if (!$feature) {
            throw new NotFoundHttpException('Feature not found');
        }

        //if ($feature === null) {
        //    $feature = new Feature('unknown', null, $provider);
        //}

        $parameters = [
            'feature'  => $feature,
            'name'     => $feature->getName(),
            'provider' => $provider->getName(),
            'config'   => $feature->getConfiguration(),
            'status'   => $feature->getStatus(),
            'class'    => $this->getFeatureClass($feature->getStatus())
        ];

        $view = $writable
            ? 'VendPheatBundle:Management:feature-rw.html.twig'
            : 'VendPheatBundle:Management:feature-ro.html.twig';

        if ($writable) {
            /**
             * @var WritableProviderInterface $provider
             */
            $form = $this->createForm(new FeatureType(), $feature);

            if ($request->isMethod('post')) {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $provider->setFeature($manager->getContext(), $feature);

                    return $this->redirect($this->generateUrl('vend_pheat_management'));
                }
            }

            $parameters['form'] = $form->createView();
        }

        return $this->render($view, $parameters);
    }

    /**
     * @param boolean|null $status
     * @return string
     */
    protected function getFeatureClass($status)
    {
        if ($status === null) {
            return 'vend_pheat_feature_unknown';
        } elseif ($status === false) {
            return 'vend_pheat_feature_inactive';
        } else {
            return 'vend_pheat_feature_active';
        }
    }
}
