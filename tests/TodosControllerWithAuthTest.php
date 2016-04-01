<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Mockery as m;
use \App\Todo;
use \App\User;

class TodosControllerWithAuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @var \App\Todo
     */
    protected $todo;

    /**
     * @var \App\User;
     */
    protected $users;

    public function setUp()
    {
        parent::setUp();
        $this->users = factory(User::class, 2)->create();
    }

    public function testUpdateUserTodo()
    {
        $this->be($this->users[0]);
        $authenticatedUser = $this->users[0];
        $authenticatedUserTodo = factory(Todo::class)->create([
            'user_id' => $authenticatedUser->id,
            'status' => Todo::STATUS_INCOMPLETE,
        ]);

        $this->call('POST', "/todos/$authenticatedUserTodo->id/update", ['status' => Todo::STATUS_COMPLETED]);
        $this->assertEquals(Todo::STATUS_COMPLETED, Todo::find($authenticatedUserTodo->id)->status);
    }

    public function testNotUpdateUserTodo()
    {
        $this->be($this->users[0]);
        $otherUser = $this->users[1];
        $otherUserTodo = factory(Todo::class)->create([
            'user_id' => $otherUser->id,
            'status' => Todo::STATUS_INCOMPLETE,
        ]);

        $this->call('POST', "/todos/$otherUserTodo->id/update", ['status' => Todo::STATUS_COMPLETED]);
        $this->assertEquals(Todo::STATUS_INCOMPLETE, Todo::find($otherUserTodo->id)->status);
    }

    public function testAjaxUpdateTitle()
    {
        $this->be($this->users[0]);
        $authenticatedUser = $this->users[0];
        $authenticatedUserTodo = factory(Todo::class)->create(['user_id' => $authenticatedUser->id]);

        $this->call('PUT', "/todos/$authenticatedUserTodo->id/title", ['title' => 'hoge']);
        $this->assertEquals('hoge', Todo::find($authenticatedUserTodo->id)->title);
    }

    public function testAjacNotUpdateTitle()
    {
        $this->be($this->users[0]);
        $otherUser = $this->users[1];
        $otherUserTodo = factory(Todo::class)->create(['user_id' => $otherUser->id]);

        $this->call('PUT', "/todos/$otherUserTodo->id/title", ['title' => 'hoge']);
        $this->assertEquals($otherUserTodo->title, Todo::find($otherUserTodo->id)->title);
    }

    public function testDeleteUsersTodo()
    {
        // user0 が認証されている
        $this->be($this->users[0]);
        $authenticatedUser = $this->users[0];
        $authenticatedUserTodo = factory(Todo::class)->create(['user_id' => $authenticatedUser->id]);

        // user0 のtodoを消そうとした場合
        $this->call('POST', "todos/$authenticatedUserTodo->id/delete");
        $this->assertEmpty(Todo::all());
    }
    
    public function testNotDeleteOtherUsersTodo()
    {
        // user0 が認証されている
        $this->be($this->users[0]);
        $otherUser = $this->users[1];
        $otherUserTodo = factory(Todo::class)->create(['user_id' => $otherUser->id]);

        $this->call('POST', "todos/$otherUserTodo->id/delete");
        $this->assertEquals(1, Todo::count());
    }


}
