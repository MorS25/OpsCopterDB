<?php

namespace OpsCopter\DB\ServerBundle\Controller;

use OpsCopter\DB\Common\Utility\ControllerGetters;
use OpsCopter\DB\ServerBundle\Entity\Server;
use OpsCopter\DB\Common\Form\Type\ConfirmType;
use OpsCopter\DB\ServerBundle\Form\Type\ServerType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServersController extends FOSRestController {

    use ControllerGetters;

    protected $em;

    public function setContainer(ContainerInterface $container = NULL) {
        parent::setContainer($container);
        if($this->container) {
            $this->em = $this->getDoctrine()->getManager();
        }
        else {
            $this->em = NULL;
        }
    }

    /**
     * Fetch all servers
     *
     * @APIDoc(
     *  resource = true,
     *  statusCodes = {
     *      200 = "Returned when successful"
     *  }
     * )
     * @View()
     *
     * @return Server[]
     */
    public function getServersAction()
    {
        $servers = $this
            ->getDoctrine()
            ->getRepository('CopterDBServerBundle:Server')
            ->findAll();

        return $servers;
    }

    /**
     * Displays a form for creating a new server
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "OpsCopter\DB\ServerBundle\Form\Type\ServerType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the server is not found"
     *   }
     * )
     *
     * @View()
     *
     * @Route(requirements={"_format"="html"})
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function newServerAction() {
        return $this->createForm(new ServerType());
    }

    /**
     * Creates a new server from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "OpsCopter\DB\ServerBundle\Form\Type\ServerType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the request has errors"
     *   }
     * )
     *
     * @View(statusCode = Codes::HTTP_BAD_REQUEST)
     *
     * @param Request $request the request object
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function postServerAction(Request $request) {
        $server = new Server(NULL);

        $form = $this->createForm(new ServerType(), $server);

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->routeRedirectView('get_server', array('id' => $server->getId()));
        }

        return $form;

    }

    /**
     * Returns a single server
     *
     * @APIDoc(
     *  output = "OpsCopter\DB\ServerBundle\Entity\Server",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the server is not found"
     *  }
     * )
     * @View(templateVar="server")
     *
     * @param int $server The ID of the server
     *
     * @return Server
     */
    public function getServerAction($id) {
        return $this->getServer($id);
    }

    /**
     * Displays a form for editing a server
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "OpsCopter\DB\ServerBundle\Form\Type\ServerType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the server is not found"
     *   }
     * )
     *
     * @View(templateVar="server")
     * @Route(requirements={"_format"="html"})
     *
     * @param string|int $server The identifier of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function editServerAction($id) {
        $server = $this->getServer($id);
        return $this->createForm(new ServerType(), $server);
    }

    /**
     * Updates a single server
     *
     * @APIDoc(
     *  resource = true,
     *  input = "OpsCopter\DB\ServerBundle\Form\Type\ServerType",
     *  statusCodes = {
     *      201 = "Returned when a new server is created",
     *      400 = "Returned when the request has errors"
     *  }
     * )
     *
     * @View()
     *
     * @param Request $request the request object
     * @param int $server The id of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function putServerAction(Request $request, $id) {
        $server = $this->getServer($id);
        $form = $this->createForm(new ServerType(), $server);

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->routeRedirectView('get_server', array('id' => $server->getId()));
        }

        return $form;
    }

    /**
     * Displays a form for removing a single server
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "OpsCopter\DB\ServerBundle\Form\Type\ConfirmType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the server is not found"
     *   }
     * )
     *
     * @Route(requirements={"_format"="html"})
     *
     * @View()
     *
     * @param int $server The ID of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function removeServerAction($id) {
        $this->getServer($id);
        return $this->createForm(new ConfirmType());
    }

    /**
     * Deletes a single server.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204 = "Returned when successful"
     *   }
     * )
     *
     * @View()
     *
     * @param Request $request the request object
     * @param string|int $server The identifier of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function deleteServerAction(Request $request, $id) {
        $server = $this->getServer($id);
        $form = $this->createForm(new ConfirmType());

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($server);
            $em->flush();

            return $this->routeRedirectView('get_servers');
        }
        return $form;
    }
}
