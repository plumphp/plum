<?php

require_once __DIR__.'/../vendor/autoload.php';

use Cocur\Plum\Reader\ArrayReader;
use Cocur\Plum\Writer\ArrayWriter;
use Cocur\Plum\Workflow;
use Cocur\Plum\WorkflowConcatenator;

$reader = new ArrayReader(['foo', 'bar', 'baz', 'qoo']);
$writer = new ArrayWriter();

$concatenator = new WorkflowConcatenator();

$workflow1 = new Workflow();
$workflow1->addWriter($concatenator);
$workflow1->process($reader);

$workflow2 = new Workflow();
$workflow2->addWriter($writer);
$workflow2->process($concatenator);

print_r($writer->getData());
