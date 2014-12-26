<?php

namespace OpsCopter\DB\ProjectBundle\Controller;

use OpsCopter\DB\Common\Utility\ControllerGetters;
use OpsCopter\DB\ProjectBundle\Entity\Project;
use OpsCopter\DB\ProjectBundle\Form\Type\ProjectType;
use OpsCopter\DB\Common\Form\Type\ConfirmType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Util\Debug;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProjectsController
 * @package OpsCopter\DB\ProjectBundle\Controller
 */
class ProjectsController extends FOSRestController
{
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
     * Fetch all projects
     *
     * @View()
     *
     * @return Project[]
     */
    public function getProjectsAction()
    {
        $projects = $this->em
            ->getRepository('CopterDBProjectBundle:Project')
            ->findAll();

        return $projects;
    }

    /**
     * Displays a form for creating a new project
     *
     * @View()
     *
     * @Route(requirements={"_format"="html"})
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function newProjectAction() {
        return $this->createForm(new ProjectType());
    }

    /**
     * Creates a new Project from the submitted data
     *
     * @View(statusCode = Codes::HTTP_BAD_REQUEST)
     *
     * @param Request $request the request object
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function postProjectAction(Request $request) {
        $project = new Project(NULL);
        $form = $this->createForm(new ProjectType(), $project);

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->routeRedirectView('get_project', array('project_id' => $project->getId()));
        }

        return $form;

    }

    /**
     * Returns a single project
     *
     * @View(templateVar="project")
     *
     * @param string|int $project_id The identifier of the project
     *
     * @return Project
     */
    public function getProjectAction($project_id) {
        return $this->getProject($project_id);
    }

    /**
     * Displays a form for editing a project
     *
     * @View(templateVar="project")
     * @Route(requirements={"_format"="html"})
     *
     * @param string|int $project_id The identifier of the project
     *
     * @return \Symfony\Component\Form\FormTypeInterface;
     */
    public function editProjectAction($project_id) {
        $project = $this->getProject($project_id);
        return $this->createForm(new ProjectType(), $project);

    }

    /**
     * Updates a single project
     *
     * @View()
     *
     * @param Request $request the request object
     * @param string|int $project_id The identifier of the project
     *
     * @return \Symfony\Component\Form\FormTypeInterface|\FOS\RestBundle\View\RouteRedirectView;
     */
    public function putProjectAction(Request $request, $project_id) {
        $project = $this->getProject($project_id);
        $form = $this->createForm(new ProjectType(), $project);

        $form->submit($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->routeRedirectView('get_project', array('project_id' => $project->getId()));
        }

        return $form;
    }

    /**
     * Displays a form for removing a single project
     *
     * @Route(requirements={"_format"="html"})
     *
     * @View()
     *
     * @param string|int $project_id The identifier of the project
     *
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function removeProjectAction($project_id) {
        $this->getProject($project_id); // Ensure a project.
        return $this->createForm(new ConfirmType());
    }

    /**
     * Deletes a single project.
     *
     * @View(statusCode = Codes::HTTP_BAD_REQUEST)
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

//
//    /**
//     * @Route("/{id}/populate-tags", name="project.tags")
//     * @param Project $project
//     */
//    public function populateTagsAction(Project $project) {
//        $github = $this->getGitHub();
//        list($user, $repo) = $this->parseGithubProjectURL($project->getUrl());
//        $tags = $github->repository()->tags($user, $repo);
//
//        $em = $this->getDoctrine()->getManager();
//
//        foreach($tags as $tag) {
//            $projectVersion = new ProjectVersion();
//            $projectVersion->setProject($project);
//            $projectVersion->setTag($tag['name']);
//            $em->persist($projectVersion);
//        }
//        $em->flush();
//        return $this->debug($tags);
//        return new Response($tags);
//    }
//
//
//    /**
//     * @return \Github\Client
//     */
//    protected function getGitHub() {
//        return $this->get('github');
//    }
//
//    protected function parseGithubProjectURL($url) {
//        if(preg_match('/github\.com\/([a-zA-Z0-9_\-]+)\/([a-zA-Z0-9_\-\.]+)\.git/', $url, $matches)) {
//            return array(rawurldecode($matches[1]), rawurldecode($matches[2]));
//        }
//    }
}
