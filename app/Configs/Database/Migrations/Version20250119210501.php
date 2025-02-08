<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119210501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding the updated_at column in pessoa table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('pessoa');   
        $table->addColumn('updated_at', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('pessoa');
        $table->dropColumn('updated_at');
    }
}
