<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TodosControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_return_a_list_of_todos()
    {
        Todo::factory(3)->create();

        $this->getJson(route('todos.index'))->assertOk()->assertJsonCount(3);
    }

    /** @test */
    public function it_can_return_a_single_todo()
    {
        Todo::factory(3)->create();

        $todo = Todo::factory()->create();

        $this->getJson(route('todos.show', $todo))->assertOk()->assertJsonFragment([
            'id' => $todo->id,
            'title' => $todo->title,
        ]);
    }

    /** @test */
    public function it_returns_todo_in_expected_format()
    {
        $todo = Todo::factory()->create();

        $this->getJson(route('todos.show', $todo))->assertOk()->assertJsonStructure([
                'id',
                'title',
                'completed_at',
                'created_at',
                'updated_at',
            ])->assertJsonFragment([
                'id' => $todo->id,
                'completed' => false
            ]);
    }

    /** @test */
    public function it_returns_correct_completed_attribute_for_completed_todo()
    {
        $todo = Todo::factory()->create([
            'completed_at' => now()->toDateTimeString()
        ]);

        $this->getJson(route('todos.show', $todo))->assertOk()->assertJsonFragment([
            'id' => $todo->id,
            'completed' => true
        ]);
    }

    /** @test */
    public function it_can_store_a_new_todo()
    {
        $title = 'What People Call "Love" Is Just A Chemical Reaction...';

        $this->postJson(route('todos.store'), [
            'title' => $title
        ])->assertOk();

        $this->assertDatabaseHas('todos', [
            'title' => $title
        ]);
    }

    /** @test */
    public function it_can_update_the_title_of_a_todo()
    {
        $todo = Todo::factory()->create();
        $title = 'What People Call "Love" Is Just A Chemical Reaction...';

        $this->putJson(route('todos.update', $todo), [
            'title' => $title
        ])->assertOk();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $title,
        ]);
    }

    /** @test */
    public function it_can_update_the_completed_status_of_a_todo_to_complete()
    {
        Carbon::setTestNow(now());
        $todo = Todo::factory()->create();

        $this->putJson(route('todos.update', $todo), [
            'title' => $todo->title,
            'completed' => true
        ])->assertOk();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $todo->title,
            'completed_at' => now()->toDateTimeString()
        ]);
    }

    /** @test */
    public function it_can_soft_delete_a_todo()
    {
        Carbon::setTestNow(now());
        $todo = Todo::factory()->create();

        $this->deleteJson(route('todos.destroy', $todo))->assertOk();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $todo->title,
            'deleted_at' => now()->toDateTimeString()
        ]);
    }
}
