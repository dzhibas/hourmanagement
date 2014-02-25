<?php

namespace Project\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class ProjectTable extends AbstractTableGateway
{
    protected $table = 'project';
    protected $logger;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Project());

        $this->initialize();
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getProject($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProject(Project $project)
    {
        $data = array(
            'artist' => $project->artist,
            'title'  => $project->title,
        );

        $id = (int)$project->id;
        if ($id == 0) {
            $this->logger->info("New project added: {$project->title} by {$project->artist}");
            $this->insert($data);
        } else {
            if ($this->getProject($id)) {
                $this->logger->info("Edit project ID {$id}: {$project->title} by {$project->artist}");
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProject($id)
    {
        $this->logger->info("Deleted project ID {$id}");
        $this->delete(array('id' => $id));
    }

}
