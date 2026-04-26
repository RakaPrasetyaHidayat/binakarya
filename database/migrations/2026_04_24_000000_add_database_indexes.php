<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add database indexes safely using raw SQL
     */
    public function up(): void
    {
        $connection = DB::getDefaultConnection();
        
        if ($connection === 'mysql') {
            $this->addMySQLIndexes();
        } elseif ($connection === 'sqlite') {
            $this->addSQLiteIndexes();
        }
    }

    private function addMySQLIndexes(): void
    {
        // Check and add indexes using raw SQL to avoid duplicate key errors
        $indexesToAdd = [
            // TABLE => [COLUMN => INDEX_NAME]
            'books' => [
                'title' => 'books_title_index',
                'slug' => 'books_slug_index',
                'author' => 'books_author_index',
                'isbn' => 'books_isbn_index',
                'category_id' => 'books_category_id_index',
                'is_published' => 'books_is_published_index',
            ],
            'posts' => [
                'title' => 'posts_title_index',
                'slug' => 'posts_slug_index',
                'category_id' => 'posts_category_id_index',
                'is_published' => 'posts_is_published_index',
                'published_at' => 'posts_published_at_index',
            ],
            'services' => [
                'title' => 'services_title_index',
                'slug' => 'services_slug_index',
                'is_active' => 'services_is_active_index',
            ],
            'categories' => [
                'name' => 'categories_name_index',
                'slug' => 'categories_slug_index',
                'type' => 'categories_type_index',
            ],
            'contacts' => [
                'email' => 'contacts_email_index',
            ],
            'pages' => [
                'slug' => 'pages_slug_index',
                'is_published' => 'pages_is_published_index',
            ],
            'menus' => [
                'parent_id' => 'menus_parent_id_index',
                'is_active' => 'menus_is_active_index',
            ],
            'audit_logs' => [
                'model_type' => 'audit_logs_model_type_index',
                'event' => 'audit_logs_event_index',
            ],
        ];

        foreach ($indexesToAdd as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column => $indexName) {
                try {
                    // Check if index already exists
                    $indexExists = DB::selectOne(
                        "SELECT * FROM INFORMATION_SCHEMA.STATISTICS 
                         WHERE Table_Schema = ? AND Table_Name = ? AND Index_Name = ?",
                        [DB::getDatabaseName(), $table, $indexName]
                    );

                    if (!$indexExists) {
                        DB::statement("ALTER TABLE `$table` ADD INDEX `$indexName` (`$column`)");
                    }
                } catch (\Exception $e) {
                    // Silently continue if index creation fails
                    continue;
                }
            }
        }
    }

    private function addSQLiteIndexes(): void
    {
        // SQLite doesn't support direct "ADD INDEX" like MySQL
        // Skip for SQLite - indexes can be added manually if needed
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        // Don't drop indexes - they improve performance
        // Manual cleanup if needed
    }
};
