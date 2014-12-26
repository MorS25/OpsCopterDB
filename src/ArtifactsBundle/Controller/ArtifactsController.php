<?php

namespace OpsCopter\DB\ArtifactsBundle\Controller;

use OpsCopter\DB\ProjectBundleForm\Type\ConfirmType;
use FOS\RestBundle\Controller\FOSRestController;
use OpsCopter\DB\ArtifactsBundle\Entity\Artifact;
use OpsCopter\DB\ArtifactsBundle\Entity\ArtifactUploadRequest;
use OpsCopter\DB\ArtifactsBundle\Form\Type\ArtifactUploadRequestType;
use OpsCopter\DB\ArtifactsBundle\Form\Type\ArtifactType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArtifactsController extends FOSRestController {


    /**
     * @View()
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postArtifactGrantAction(Request $request) {
        $uploadRequest = new ArtifactUploadRequest();
        $form = $this->createForm(new ArtifactUploadRequestType(), $uploadRequest);

        $form->submit($request);
        $mapper = $this->getMapper();

        if($form->isValid()) {
            $mapper->validateUploadGrantRequest($uploadRequest);
            return $this->view($mapper->getUploadGrant($uploadRequest), Response::HTTP_CREATED);
        }

        return $form;
    }

    /**
     * @View()
     * @param Request $request
     * @param $artifact_id
     * @return \FOS\RestBundle\View\View
     */
    public function getArtifactGrantAction(Request $request, $artifact_id) {
        $artifact = $this->getArtifact($artifact_id);
        $mapper = $this->getMapper();
        $mapper->validateDownloadGrantRequest($artifact);

        return $this->view($mapper->getDownloadGrant($artifact));
    }

    /**
     * @View()
     */
    public function getArtifactsAction() {
        return $this->getRepository()->findAll();
    }

    /**
     * @View()
     * @param $artifact_id
     * @return Artifact
     */
    public function getArtifactAction($artifact_id) {
        return $this->getArtifact($artifact_id);
    }


    /**
     * @View()
     * @return \Symfony\Component\Form\Form
     */
    public function newArtifactAction() {
        $artifact = new Artifact();
        return $this->createForm(new ArtifactType(), $artifact);
    }

    /**
     * @View()
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form
     */
    public function postArtifactAction(Request $request) {
        $artifact = new Artifact();
        $form = $this->createForm(new ArtifactType(), $artifact);

        $form->submit($request);

        if($form->isValid()) {
            $artifact->setType('test');
            $em = $this->getManager();
            $em->persist($artifact);
            $em->flush();
            return $this->routeRedirectView('get_artifact', array('artifact_id' => $artifact->getId()));
        }
        return $form;
    }

    /**
     * @param Request $request
     * @param $artifact_id
     * @return \Symfony\Component\Form\Form
     */
    public function editArtifactAction(Request $request, $artifact_id) {
        $artifact = $this->getArtifact($artifact_id);
        return $this->createForm(new ArtifactType(), $artifact);
    }

    /**
     * @View()
     * @param Request $request
     * @param $artifact_id
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form
     */
    public function putArtifactAction(Request $request, $artifact_id) {
        $artifact = $this->getArtifact($artifact_id);
        $form = $this->createForm(new ArtifactType(), $artifact);

        $form->submit($request);

        if($form->isValid()) {
            $em = $this->getManager();
            $em->persist($artifact);
            $em->flush();
            return $this->routeRedirectView('get_artifact', array('artifact_id' => $artifact->getId()));
        }

        return $form;
    }

    /**
     * @View()
     * @param $artifact_id
     * @return \Symfony\Component\Form\Form
     */
    public function removeArtifactAction($artifact_id) {
        $this->getArtifact($artifact_id);
        return $this->createForm(new ConfirmType());
    }

    /**
     * @View()
     * @param $artifact_id
     */
    public function deleteArtifactAction(Request $request, $artifact_id) {
        $artifact = $this->getArtifact($artifact_id);
        $form = $this->createForm(new ConfirmType());

        $form->submit($request);

        if($form->isValid()) {
            $em = $this->getManager();
            $em->remove($artifact);
            $em->flush();
            return $this->routeRedirectView('get_artifacts');
        }

        return $form;
    }

    /**
     * @param $artifact_id
     * @return Artifact
     */
    protected function getArtifact($artifact_id) {
        $artifact = $this->getRepository()->find($artifact_id);
        if(!$artifact) {
            throw new NotFoundHttpException('Artifact not found');
        }
        return $artifact;
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository() {
        return $this->getDoctrine()->getRepository('CopterDBArtifactsBundle:Artifact');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getManager() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \OpsCopter\DB\ArtifactsBundle\Grants\S3GrantsManager
     */
    protected function getMapper() {
        return $this->get('artifacts.mapper');
    }
}
