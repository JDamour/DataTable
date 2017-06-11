<?php

namespace LaravelEnso\DataTable\app\Classes;

use LaravelEnso\DataTable\app\Classes\TableStructure;

class TableInit
{
    private $data;

    public function __construct(TableStructure $tableStructure)
    {
        $this->data = $tableStructure->getData();
        $this->run();
    }

    public function getData()
    {
        return $this->data;
    }

    private function run()
    {
        $this->setHeader()
            ->setResponsivePriority()
            ->setNotSearchable()
            ->setNotSortable()
            ->computeTotals()
            ->setEditable()
            ->computeCrtNo()
            ->setActionButtons();

        unset($this->data['enumMappings']);
        unset($this->data['customActionButtons']);
    }

    private function setHeader()
    {
        $this->data['header'] = [];

        foreach ($this->data['columns'] as &$value) {
            $this->data['header'][] = array_shift($value);
        }

        return $this;
    }

    private function setResponsivePriority()
    {
        if (!isset($this->data['responsivePriority'])) {
            return $this;
        }

        $this->setSecondaryPriorityColumns();
        $this->setPrimaryPriorityColumns();
        unset($this->data['responsivePriority']);

        return $this;
    }

    private function setSecondaryPriorityColumns()
    {
        $priority = count($this->data['responsivePriority']) + 1;

        foreach ($this->data['columns'] as &$column) {
            $column['responsivePriority'] = $priority;
        }
    }

    private function setPrimaryPriorityColumns()
    {
        $priority = 1;

        foreach ($this->data['responsivePriority'] as &$column) {
            $this->data['columns'][$column]['responsivePriority'] = $priority++;
        }
    }

    private function setNotSearchable()
    {
        if (!isset($this->data['notSearchable'])) {
            return $this;
        }

        foreach ($this->data['notSearchable'] as $column) {
            $this->data['columns'][$column]['searchable'] = false;
        }

        unset($this->data['notSearchable']);

        return $this;
    }

    private function setNotSortable()
    {
        if (!isset($this->data['notSortable'])) {
            return $this;
        }

        foreach ($this->data['notSortable'] as $column) {
            $this->data['columns'][$column]['sortable'] = false;
        }

        unset($this->data['notSortable']);

        return $this;
    }

    private function computeTotals()
    {
        $totals = [];

        foreach ($this->data['totals'] as $key => $column) {
            $field = $this->data['columns'][$column]['name'];
            $totals[$column] = $field;
        }

        $this->data['totals'] = $totals;

        return $this;
    }

    private function setEditable()
    {
        if (!isset($this->data['editable'])) {
            return $this;
        }

        foreach ($this->data['editable'] as $key => $column) {
            $this->data['columns'][$column]['class'] = trim(
                (isset($this->data['columns'][$column]['class']) ? $this->data['columns'][$column]['class'] : '').' editable'
            );

            $this->data['columns'][$column]['editField'] = $this->data['columns'][$column]['name'];
            $this->data['editable'][$key] = ['name' => $this->data['columns'][$column]['name']];
        }

        return $this;
    }

    private function getEditableLabel($index)
    {
        $labelArray = explode('.', $this->data['columns'][$index]['name']);

        return end($labelArray);
    }

    private function computeCrtNo()
    {
        if (!isset($this->data['crtNo'])) {
            return $this;
        }

        $this->data = (new CrtNoComputor($this->data))->getData();

        return $this;
    }

    private function setActionButtons()
    {
        if (!isset($this->data['actionButtons'])) {
            return $this;
        }

        $this->data['actionButtons'] = (new ActionButtonBuilder($this->data))->getData();
    }
}
