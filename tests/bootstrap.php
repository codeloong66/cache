<?php

// 先引入自动加载机制
$loader = require(__DIR__ . '/../../../bootstrap.php');
$loader->addPsr0(__DIR__ . '/test', 'Cache\\Tests\\');
