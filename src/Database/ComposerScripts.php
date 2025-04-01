<?php

namespace PSS0225Seplag\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\DriverManager;
use Composer\Script\Event;
use PSS0225Seplag\Config\DBConfig;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class ComposerScripts
{
    public static function getEntityManager()
    {
        $dbParams = DBConfig::getParam();
        $cache = new ArrayAdapter();
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . '/../Entities'],
            ($_ENV['APP_ENV'] ?? 'prod') === 'dev',
            null,
            $cache,
            false
        );

        $connection = DriverManager::getConnection($dbParams, $config);
        return new EntityManager($connection, $config);
    }

    public static function setupDatabase(Event $event)
    {
        $io = $event->getIO();

        try {
            $io->write('Verificando configuração do banco de dados...');

            $entityManager = self::getEntityManager();
            $schemaManager = $entityManager->getConnection()->createSchemaManager();

            if (!in_array(DBConfig::getDbName(), $schemaManager->listDatabases())) {
                $io->write('Banco de dados não existe, tentando criar...');
                $schemaManager->createDatabase(DBConfig::getDbName());
                $io->write('Banco de dados criado com sucesso!');
            }

            $entityManager->getConnection()->connect();
            $tables = $schemaManager->listTables();

            if (empty($tables)) {
                $io->write('Criando tabelas...');
                self::createSchema($event);
            } else {
                $io->write('Banco de dados já contém tabelas.');
            }
        } catch (\Exception $e) {
            $io->writeError('<error>Erro: ' . $e->getMessage() . '</error>');
        }
    }


    public static function createSchema(Event $event)
    {
        $entityManager = self::getEntityManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->createSchema($metadata);
            $event->getIO()->write('Tabelas criadas com sucesso!');
        } else {
            $event->getIO()->write('Nenhuma entidade encontrada para criar tabelas.');
        }
    }

    public static function updateSchema(Event $event)
    {
        $entityManager = self::getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->updateSchema($metadata);
        $event->getIO()->write('Esquema do banco de dados atualizado!');
    }

    public static function dropSchema(Event $event)
    {
        $entityManager = self::getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $event->getIO()->write('Tabelas removidas com sucesso!');
    }
}
