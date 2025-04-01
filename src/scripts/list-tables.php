<?php
// scripts/list-tables.php
require __DIR__ . '/vendor/autoload.php';

$entityManager = (new PSS0225Seplag\Database\ComposerScripts())->getEntityManager();
$schemaManager = $entityManager->getConnection()->createSchemaManager();

print_r($schemaManager->listTables());