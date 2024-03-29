<?php

namespace OpsCopter\DB\ServerBundle\Controller;

use OpsCopter\DB\ServerBundle\Entity\Server;
use OpsCopter\DB\Common\Form\Type\ConfirmType;
use OpsCopter\DB\ServerBundle\Form\Type\ServerType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServersController extends FOSRestController {

    /**
     * Fetch all servers
     *
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

            return $this->routeRedirectView('get_server', array('server_id' => $server->getId()));
        }

        return $form;

    }

    /**
     * Returns a single server
     *
     * @View(templateVar="server")
     *
     * @param int $server_id The ID of the server
     *
     * @return Server
     */
    public function getServerAction($server_id) {
        return $this->getServer($server_id);
    }

    /**
     * Displays a form for editing a server
     *
     * @View(templateVar="server")
     * @Route(requirements={"_format"="html"})
     *
     * @param int $server_id The identifier of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function editServerAction($server_id) {
        $server = $this->getServer($server_id);
        return $this->createForm(new ServerType(), $server);
    }

    /**
     * Updates a single server
     *
     * @View()
     *
     * @param Request $request the request object
     * @param int $server_id The id of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function putServerAction(Request $request, $server_id) {
        $server = $this->getServer($server_id);
        $form = $this->createForm(new ServerType(), $server);

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->routeRedirectView('get_server', array('server_id' => $server->getId()));
        }

        return $form;
    }

    /**
     * Displays a form for removing a single server
     *
     * @Route(requirements={"_format"="html"})
     *
     * @View()
     *
     * @param int $server_id The ID of the server
     *
     * @return \Symfony\Component\Form\Form
     */
    public function removeServerAction($server_id) {
        $this->getServer($server_id);
        return $this->createForm(new ConfirmType());
    }

    /**
     * Deletes a single server.
     *
     * @View()
     *
     * @param Request $request the request object
     * @param int $server_id The identifier of the server
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function deleteServerAction(Request $request, $server_id) {
        $server = $this->getServer($server_id);
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

    /**
     * Get a single server by ID
     *
     * @param $id
     * @return Server
     */
    protected function getServer($id) {
        if($server = $this->getDoctrine()->getManager()->find('CopterDBServerBundle:Server', $id)) {
            return $server;
        }
        throw new NotFoundHttpException('Server not found.');
    }
}
