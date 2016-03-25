<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TodoTest extends \TestCase
{
    use DatabaseMigrations;

    public function testGetIncompleteTodos()
    {
        $this->call('GET', '/todos');
        factory(App\Todo::class)->create(['status' => 1]);
        $incompleteTodo = \App\Todo::all();

        $this->assertViewHas('incompleteTodos', $incompleteTodo);
    }

    public function testGetCompletedTodos()
    {
        $this->visit('/todos');
        factory(\App\Todo::class)->create(['status' => 2]);
        $completedTodo = \App\Todo::all();

        $this->assertViewHas('completedTodos', $completedTodo);
    }

    public function testGetTrashedTodos()
    {
        $this->visit('/todos');
        factory(\App\Todo::class)->create(['deleted_at' => new DateTime()]);
        $trashedTodo = \App\Todo::all();

        $this->assertViewHas('trashedTodos', $trashedTodo);
    }


    public function testCreate()
    {
        $todo = factory(App\Todo::class)->make(['status' => 1]);

        $this->visit('/todos')
            ->type($todo->title, 'title')
            ->press('追加');

        $this->seeInDatabase('todos', [
            'title' => $todo->title,
            'status' => $todo->status,
            'created_at' => $todo->created_at,
            'updated_at' => $todo->updated_at,
        ]);
    }

    public function testDelete()
    {
//        $todo = factory(\App\Todo::class)->create(['title' => 'hoge']);
        $todo = \App\Todo::create([
            'title' => 'title',
            'status' => 1,
        ]);
        if (\App\Todo::count() != 1) {
            $this->assertFalse('no data');
        }

        $this->route('POST', 'todos.delete', ['id' => $todo->id])
            ->isRedirection();

        if (\App\Todo::count() != 0) {
            $this->assertFalse('don`t delete data');
        }

    }

}