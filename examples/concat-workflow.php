<?php

require_once __DIR__.'/../vendor/autoload.php';

use Plum\Plum\Reader\ArrayReader;
use Plum\Plum\Writer\ArrayWriter;
use Plum\Plum\Workflow;
use Plum\Plum\WorkflowConcatenator;

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
