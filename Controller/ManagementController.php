<?php

namespace Vend\PheatBundle\Controller;

use Pheat\Feature\Feature;
use Pheat\Feature\FeatureInterface;
use Pheat\Manager;
use Pheat\Provider\ProviderInterface;
use Pheat\Provider\WritableProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $manager   = $this->getManager();
        $forms = $this->getForms($manager);

        if ($request->isMethod('post')) {
            $redirect = true;

            foreach ($forms as $feature => $providerEntry) {
                foreach ($providerEntry as $provider => $entry) {
                    /**
                     * @var Form $form
                     */
                    $form = $entry['form'];

                    /**
                     * @var WritableProviderInterface $provider
                     */
                    $provider = $entry['provider'];

                    $form->handleRequest($request);

                    /**
                     * @var $session Session
                     */
                    $session = $this->get('session');

                    if ($form->isValid()) {
                        $session->getFlashBag()->add('success', 'Feature configuration saved');
                        $provider->setFeature($manager->getContext(), $form->getData());
                    } else {
                        $session->getFlashBag()->add('warning', 'Feature configuration failed to save');
                        $redirect = false;
                    }
                }
            }

            if ($redirect) {
                return $this->redirect($this->generateUrl('vend_pheat_management'));
            }
        }

        $content = [];

        foreach ($manager->getFeatureSet()->getAll() as $name => $_) {
            $content[$name] = [];

            foreach ($manager->getProviders() as $provider) {
                /**
                 * @var ProviderInterface $provider
                 */
                $providerName = $provider->getName();

                /**
                 * @var FeatureInterface $feature
                 */
                $feature = $manager->getFeatureSet()->getFeatureFromProvider($name, $provider);

                $parameters = [
                    'feature'  => $feature,
                    'name'     => $name,
                    'provider' => $providerName,
                    'config'   => $feature->getConfiguration(),
                    'status'   => $feature->getStatus(),
                    'class'    => $this->getFeatureClass($feature->getStatus())
                ];

                if ($provider instanceof WritableProviderInterface && isset($forms[$name][$providerName])) {
                    $parameters['form'] = $forms[$name][$providerName]['form']->createView();
                    $view = 'VendPheatBundle:Management:feature-rw.html.twig';
                } else {
                    $view = 'VendPheatBundle:Management:feature-ro.html.twig';
                }

                $content[$name][$providerName] = $this->renderView($view, $parameters);
            }
        }

        return $this->render('VendPheatBundle:Management:index.html.twig', [
            'providers' => $manager->getProviders(),
            'features'  => $manager->getFeatureSet(),
            'content'   => $content
        ]);
    }

    /**
     * @param Manager $manager
     * @return array<array<WritableProviderInterface|Form>>
     */
    protected function getForms(Manager $manager)
    {
        $forms   = [];

        foreach ($manager->getFeatureSet()->getAll() as $name => $_) {
            $forms[$name] = [];

            foreach ($manager->getProviders() as $provider) {
                /**
                 * @var ProviderInterface $provider
                 */
                if (!$provider instanceof WritableProviderInterface) {
                    continue;
                }

                $providerName = $provider->getName();
                $feature = $manager->getFeatureSet()->getFeatureFromProvider($name, $provider, new Feature($name, null, $provider));

                $form = $this->createForm(new FeatureType($providerName, $name, $manager), $feature);

                $forms[$name][$providerName] = [
                    'provider' => $provider,
                    'form'     => $form
                ];
            }
        }

        return $forms;
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
