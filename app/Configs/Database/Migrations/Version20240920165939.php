<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920165939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the initial table pessoa';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('pessoa');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 100]);
        $table->addColumn('age', 'integer');
        $table->addColumn('email', 'string', ['length' => 100]);
        $table->addColumn('cell', 'string', ['length' => 13]);
        $table->addUniqueIndex(['email'], 'unique_email');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('pessoa');
    }
}
