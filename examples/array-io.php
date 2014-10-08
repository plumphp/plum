<?php

require_once __DIR__.'/../vendor/autoload.php';

use FlorianEc\Plum\Writer\ArrayWriter;
use FlorianEc\Plum\Workflow;
use FlorianEc\Plum\Reader\ArrayReader;
use FlorianEc\Plum\Converter\CallbackConverter;
use FlorianEc\Plum\Filter\CallbackFilter;

$reader = new ArrayReader(['foobar', 'qoobar', 'bazbaz']);

$writer1 = new ArrayWriter();
$writer2 = new ArrayWriter();

$workflow = new Workflow();
$workflow->addConverter(new CallbackConverter(function ($item) { return strtoupper($item); }));
$workflow->addWriter($writer1);
$workflow->addFilter(new CallbackFilter(function ($item) { return $item != 'BAZBAZ'; }));
$workflow->addWriter($writer2);
$workflow->process($reader);

print_r($writer1->getData());
print_r($writer2->getData());
