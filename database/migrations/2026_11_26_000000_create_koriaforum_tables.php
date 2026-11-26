<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koriaforum_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description');
            $table->boolean('is_reports')->default(false); // catégorie « signalements de trahison »
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('koriaforum_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('author_id');
            $table->string('title');
            // Champs des signalements de trahison
            $table->string('traitor', 16)->nullable();
            $table->string('faction', 40)->nullable();
            $table->date('betrayal_date')->nullable();
            $table->text('losses')->nullable();
            $table->text('proofs')->nullable();
            $table->string('status', 10)->nullable(); // pending | accepted | refused
            $table->text('staff_note')->nullable();
            $table->unsignedInteger('staff_id')->nullable();
            $table->timestamp('last_post_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('koriaforum_categories')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('staff_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('koriaforum_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('topic_id');
            $table->unsignedInteger('author_id');
            $table->text('content');
            $table->boolean('is_verdict')->default(false);
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('koriaforum_topics')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $now = now();
        DB::table('koriaforum_categories')->insert([
            ['slug' => 'recrutement', 'name' => 'Recrutement de factions', 'description' => 'Présente ta faction et recrute. Rappel : forum trahison = ban + remboursement.', 'is_reports' => false, 'position' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'trahison', 'name' => 'Signalements de trahison', 'description' => 'Un membre a pillé ta base ? Poste ton signalement avec preuves. Le staff valide ou refuse.', 'is_reports' => true, 'position' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'factions', 'name' => 'Vie des factions', 'description' => 'Alliances, guerres, annonces diplomatiques.', 'is_reports' => false, 'position' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'general', 'name' => 'Général', 'description' => 'Tout le reste.', 'is_reports' => false, 'position' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('koriaforum_posts');
        Schema::dropIfExists('koriaforum_topics');
        Schema::dropIfExists('koriaforum_categories');
    }
};
