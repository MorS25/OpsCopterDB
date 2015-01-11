<?php

namespace OpsCopter\DB\ProjectBundle\Controller;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use OpsCopter\DB\ProjectBundle\Entity\ProjectStub;
use OpsCopter\DB\Common\Form\Type\ConfirmType;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use OpsCopter\DB\ProjectBundle\Form\Type\ProjectUriType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OpsCopter\DB\ProjectBundle\ProjectTypeManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProjectsController
 * @package OpsCopter\DB\ProjectBundle\Controller
 */
class ProjectsController extends FOSRestController
{

    /**
     * Fetch all projects
     *
     * @REST\View()
     *
     * @return Project[]
     */
    public function getProjectsAction()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('CopterDBProjectBundle:Project')
            ->findAll();
    }

    /**
     * Displays a form for creating a new project
     *
     * @REST\View()
     *
     * @Route(requirements={"_format"="html"})
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function newProjectAction() {
        return $this->createForm(new ProjectUriType(), new ProjectStub());
    }

    /**
     * Creates a new Project from the submitted data
     *
     * @REST\View(statusCode = Response::HTTP_BAD_REQUEST)
     *
     * @param Request $request the request object
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function postProjectAction(Request $request) {
        $projectStub = new ProjectStub();
        $form = $this->createForm(new ProjectUriType(), $projectStub);

        $form->handleRequest($request);
        if($form->isValid()) {
            $manager = $this->getProjectManager();
            $provider = $manager->getProviderByUri($projectStub->getUri());
            $project = $provider->createProjectFromUri($projectStub->getUri());
            $provider->syncProjectWithProvider($project);

            $validator = $this->get('validator');
            $errors = $validator->validate($project);

            if(count($errors) === 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();

                return $this->routeRedirectView('get_project', array('project_id' => $project->getId()));
            }
            else {
                // Notify the user about any project level validation errors that may have happened.
                foreach($errors as $error) {
                    $error = new FormError($error->getMessage());
                    $form->addError($error);
                }
            }
        }

        return $form;
    }

    /**
     * Returns a single project
     *
     * @REST\View(templateVar="project")
     *
     * @param string|int $project_id The identifier of the project
     *
     * @return Project
     */
    public function getProjectAction($project_id) {
        return $this->getProject($project_id);
    }

    /**
     * Displays a form for removing a single project
     *
     * @Route(requirements={"_format"="html"})
     *
     * @REST\View()
     *
     * @param string|int $project_id The identifier of the project
     *
     * @return \Symfony\Component\Form\Form
     */
    public function removeProjectAction($project_id) {
        $this->getProject($project_id); // Ensure a project.
        return $this->createForm(new ConfirmType());
    }

    /**
     * Deletes a single project.
     *
     * @REST\View(statusCode = Response::HTTP_BAD_REQUEST)
     *
     * @param Request $request the request object
     * @param string|int $project_id The identifier of the project
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function deleteProjectAction(Request $request, $project_id) {
        $project = $this->getProject($project_id);
        $form = $this->createForm(new ConfirmType());

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            return $this->routeRedirectView('get_projects');
        }
        return $form;
    }

    protected function getProject($id) {
        if($project = $this->getDoctrine()->getManager()->find('CopterDBProjectBundle:Project', $id)) {
            return $project;
        }
        throw new NotFoundHttpException('Project not found');
    }

    /**
     * @return ProjectTypeManager
     */
    protected function getProjectManager() {
        return $this->get('copter_db_project.type_manager');
    }
}
