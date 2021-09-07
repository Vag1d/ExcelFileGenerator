<?php
namespace App\Entity;

class Add
{
    protected $add;
    protected $dueDate;


    public function getAdd()
    {
        return $this->add;
    }

    public function setAdd($add)
    {
        $this->add = $add;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate = null)
    {
        $this->dueDate = $dueDate;
    }
}
