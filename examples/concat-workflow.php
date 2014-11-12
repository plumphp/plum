<?php

require_once __DIR__.'/../vendor/autoload.php';

use Cocur\Plum\Reader\ArrayReader;
use Cocur\Plum\Writer\ArrayWriter;
use Cocur\Plum\Workflow;
use Cocur\Plum\Writer\WorkflowWriter;
use Cocur\Plum\Reader\WorkflowReader;

$reader = new ArrayReader(['foo', 'bar', 'baz', 'qoo']);
$writer = new ArrayWriter();

$workflowReader = new WorkflowReader();
$workflowWriter = new WorkflowWriter($workflowReader);

$workflow1 = new Workflow();
$workflow1->addWriter($workflowWriter);
$workflow1->process($reader);

$workflow2 = new Workflow();
$workflow2->addWriter($writer);
$workflow2->process($workflowReader);

print_r($writer->getData());
