<?php

namespace Project\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Project\Model\Project;
use Project\Form\ProjectForm;

class ProjectController extends AbstractActionController
{
    protected $projectTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'projects' => $this->getProjectTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new ProjectForm();
        $form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $project = new Project();
            $form->setInputFilter($project->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $project->exchangeArray($form->getData());
                $this->getProjectTable()->saveProject($project);

                // Redirect to list of projects
                return $this->redirect()->toRoute('project');
            }
        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('project', array('action'=>'add'));
        }
        $project = $this->getProjectTable()->getProject($id);

        $form = new ProjectForm();
        $form->bind($project);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getProjectTable()->saveProject($project);

                // Redirect to list of projects
                return $this->redirect()->toRoute('project');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('project');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $this->getProjectTable()->deleteProject($id);
            }

            // Redirect to list of projects
            return $this->redirect()->toRoute('project');
        }

        return array(
            'id' => $id,
            'project' => $this->getProjectTable()->getProject($id)
        );
    }

    public function getProjectTable()
    {
        if (!$this->projectTable) {
            $sm = $this->getServiceLocator();
            $this->projectTable = $sm->get('Project\Model\ProjectTable');
        }
        return $this->projectTable;
    }    
}
