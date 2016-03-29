<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\View\Factory;
use App\Todo;
use App\User;
use App\Http\Controllers\TodosController;
use \Mockery as m;


class TodoTest extends \TestCase
{
    // ミドルウェアを通さない
    // ここではログインを省略するのが目的
    use WithoutMiddleware;

    /**
     * @var \App\Todo
     */
    protected $todoMock;


    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->todoMock = m::mock(Todo::class);

        // 本物のTodoクラスを参照しないようにする
        App::instance(Todo::class, $this->todoMock);
    }

    public function tearDown()
    {
        m::close();
    }


    public function testIndex()
    {
        $user = new User(['id' => 1]);

        // user id をモックして返す
        // \Auth::shouldReceive('check')->andReturn(true);
        \Auth::shouldReceive('user')
            ->withNoArgs()
            ->once()
            ->andReturn($user);

        $this->todoMock
            ->shouldReceive('getTodos')
            ->with($user->id, Todo::STATUS_INCOMPLETE)
            ->andReturn('incompleteTodo');

        $this->todoMock
            ->shouldReceive('getTodos')
            ->with($user->id, Todo::STATUS_COMPLETED)
            ->andReturn('completedTodo');

        $this->todoMock
            ->shouldReceive('getTrashed')
            ->with($user->id)
            ->andReturn('trashedTodo');

        // User無しで動作しているため、実際にviewに渡らないようにモックする
        // with の第三引数は\Viewがデフォルトで空の配列を返すため
        \View::shouldReceive('make')
            ->with('todos.index', [
                'incompleteTodos' => 'incompleteTodo',
                'completedTodos' => 'completedTodo',
                'trashedTodos' => 'trashedTodo'
            ], []);

        $this->call('GET', '/todos');
    }

    public function testStore()
    {
        
    }




//    use DatabaseMigrations;
//
//    public function testGetIncompleteTodos()
//    {
//        $this->call('GET', '/todos');
//        factory(App\Todo::class)->create(['status' => 1]);
//        $incompleteTodo = \App\Todo::all();
//
//        $this->assertViewHas('incompleteTodos', $incompleteTodo);
//    }
//
//    public function testGetCompletedTodos()
//    {
//        $this->visit('/todos');
//        factory(\App\Todo::class)->create(['status' => 2]);
//        $completedTodo = \App\Todo::all();
//
//        $this->assertViewHas('completedTodos', $completedTodo);
//    }
//
//    public function testGetTrashedTodos()
//    {
//        $this->visit('/todos');
//        factory(\App\Todo::class)->create(['deleted_at' => new DateTime()]);
//        $trashedTodo = \App\Todo::all();
//
//        $this->assertViewHas('trashedTodos', $trashedTodo);
//    }
//
//
//    public function testCreate()
//    {
//        $todo = factory(App\Todo::class)->make(['status' => 1]);
//
//        $this->visit('/todos')
//            ->type($todo->title, 'title')
//            ->press('追加');
//
//        $this->seeInDatabase('todos', [
//            'title' => $todo->title,
//            'status' => $todo->status,
//            'created_at' => $todo->created_at,
//            'updated_at' => $todo->updated_at,
//        ]);
//    }
//
//    public function testDelete()
//    {
////        $todo = factory(\App\Todo::class)->create(['title' => 'hoge']);
//        $todo = \App\Todo::create([
//            'title' => 'title',
//            'status' => 1,
//        ]);
//        if (\App\Todo::count() != 1) {
//            $this->assertFalse('no data');
//        }
//
//        $this->route('POST', 'todos.delete', ['id' => $todo->id])
//            ->isRedirection();
//
//        if (\App\Todo::count() != 0) {
//            $this->assertFalse('don`t delete data');
//        }
//
//    }

}