<?php

namespace Manuel\Bundle\DevAccessBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 *
 * @Route("/dev-access")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/start", name="manuel_dev_access_start_access")
     */
    public function indexAction(Request $request)
    {
        $this->checkRoles();

        $this->get('manuel.dev_access.security.access_config')->start($request);

        $this->addFlash(
            'success',
            'Se ha inicado el acceso a la plataforma desde el entorno de desarrollo de manera segura.'
        );

        return $this->redirectToRoute('manuel_dev_access_config');
    }

    /**
     * @Route("/finish", name="manuel_dev_access_finish_access")
     */
    public function finishAction(Request $request)
    {
        $this->checkRoles();

        $this->get('manuel.dev_access.security.access_config')->finish($request);

        $this->addFlash(
            'success',
            'Se ha finalizado el acceso a la plataforma desde el entorno de desarrollo!!!'
        );

        return $this->redirectToRoute('manuel_dev_access_config');
    }

    /**
     * @Route("/finish-all", name="manuel_dev_access_finish_all_access")
     */
    public function finishAllAction(Request $request)
    {
        $this->checkRoles();

        $this->get('manuel.dev_access.security.access_config')->finishForAll();

        $this->addFlash(
            'success',
            'Se ha finalizado el acceso a la plataforma a todos los usuarios desde el entorno de desarrollo!!!'
        );

        return $this->redirectToRoute('manuel_dev_access_config');
    }

    /**
     * @Route("/config", name="manuel_dev_access_config")
     */
    public function configAction(Request $request)
    {
        $this->checkRoles();

        return $this->render('@DevAccess/Default/index.html.twig');
    }

    private function checkRoles()
    {
        $accessRoles = $this->getParameter('manuel.dev_access.security.roles');
        array_walk($accessRoles, [$this, 'denyAccessUnlessGranted']);
    }
}
