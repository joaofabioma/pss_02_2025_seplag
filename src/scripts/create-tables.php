<?php
require __DIR__ . '/vendor/autoload.php';

use PSS0225Seplag\Database\ComposerScripts;
use Doctrine\ORM\Tools\SchemaTool;

try {
    $entityManager = ComposerScripts::getEntityManager();
    $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

    if (empty($metadata)) {
        throw new RuntimeException("Nenhuma entidade encontrada para criar tabelas.");
    }

    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->createSchema($metadata);

    echo "Tabelas criadas com sucesso!" . PHP_EOL;
    exit(0);
} catch (Exception $e) {
    fwrite(STDERR, "ERRO: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
