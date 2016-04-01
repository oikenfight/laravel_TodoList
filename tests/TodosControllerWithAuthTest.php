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
     * @var \App\Todo
     */
    protected $todoMock;
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

//        $this->todoMock = m::mock(Todo::class);
//        App::instance(Todo::class, $this->todoMock);
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testDeleteUsersTodo()
    {
        DB::enableQueryLog();

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
