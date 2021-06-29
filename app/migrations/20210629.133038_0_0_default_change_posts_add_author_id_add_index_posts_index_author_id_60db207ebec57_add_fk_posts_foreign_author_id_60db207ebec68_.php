<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault89c21f4ad4de49dcddbdead3cf8a1f1a extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('posts')
            ->addColumn('author_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["author_id"], [
                'name'   => 'posts_index_author_id_60db207ebec57',
                'unique' => false
            ])
            ->addForeignKey(["author_id"], 'users', ["id"], [
                'name'   => 'posts_foreign_author_id_60db207ebec68',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->update();
        
        $this->table('comments')
            ->addColumn('post_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('author_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["post_id"], [
                'name'   => 'comments_index_post_id_60db207ebf78c',
                'unique' => false
            ])
            ->addIndex(["author_id"], [
                'name'   => 'comments_index_author_id_60db207ebf7bf',
                'unique' => false
            ])
            ->addForeignKey(["post_id"], 'posts', ["id"], [
                'name'   => 'comments_foreign_post_id_60db207ebf798',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->addForeignKey(["author_id"], 'users', ["id"], [
                'name'   => 'comments_foreign_author_id_60db207ebf7c6',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('comments')
            ->dropForeignKey(["post_id"])
            ->dropForeignKey(["author_id"])
            ->dropIndex(["post_id"])
            ->dropIndex(["author_id"])
            ->dropColumn('post_id')
            ->dropColumn('author_id')
            ->update();
        
        $this->table('posts')
            ->dropForeignKey(["author_id"])
            ->dropIndex(["author_id"])
            ->dropColumn('author_id')
            ->update();
    }
}
