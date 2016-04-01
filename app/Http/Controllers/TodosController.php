<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Todo;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use DateTime;

use Illuminate\Support\Facades\Redirect;

class TodosController extends Controller
{
    //
    /**
     * @var Todo
     */
    protected $todo;

    /**
     * コンストラクタ
     * @return void
     */
    public function __construct(Todo $todo, Request $request)
    {
        $this->todo = $todo;
        $this->user = \Auth::user();
    }

    /**
     * Todoリストページを表示する
     * @param Request $request
     * @return array
     */
    public function index()
    {
        // viewを生成する
        $incompletedTodos = $this->todo->getTodos($this->user->id, Todo::STATUS_INCOMPLETE);
        $completedTodos = $this->todo->getTodos($this->user->id, Todo::STATUS_COMPLETED);
        $trashedTodos = $this->todo->getTrashed($this->user->id);

        return view('todos.index', [
            'incompleteTodos' => $incompletedTodos,
            'completedTodos' => $completedTodos,
            'trashedTodos' => $trashedTodos,
        ]);
    }


    /**
     * 新規Todoを追加する。
     * @retuen void
     */
    public function store()
    {
        // フォームの入力データを項目名を指定して追加する
        $input = Input::only(['title']);

        // バリデーションルールの定義
        $rules = [
            'title' => 'required|min:3|max:255', // titleは3文字以上255文字以下
        ];

        // バリデーターを生成する
        $validator = Validator::make($input, $rules);
        // バリデーションを行う
        if ($validator->fails()) {
            // バリデーションに失敗したら、バリデーションのエラー情報とフォームの入力値を追加してリストページにリダイレクトする
            return Redirect::route('todos.index')->withErrors($validator)->withInput();
        }

        // Todoデータを作成する
        $this->todo->create([
            'title' => $input['title'],
            'status' => Todo::STATUS_INCOMPLETE,
            'user_id' => $this->user['id'],
        ]);

        // index にリダイレクトする
        return Redirect::route('todos.index');
    }


    public function update($id)
    {
        $todo = $this->todo->find($id);
        if ($todo->user_id !== $this->user->id) {
            return Redirect::route('todos.index');
        }

        // 入力データを取得する
        $input = Input::only(['status']);

        // バリデーションルールの定義
        $rules = [
            "status" => ['required', 'numeric', 'min:1', 'max:2'],
        ];
        // バリデーションを実行する
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Redirect::route('todos.index')->withError($validator)->withInput();
        }

        // statusが指定されていたら
        if ($input['status'] !== null) {
            // statusとcompleted_atカラムを更新する
            $todo = $todo->fill([
                'status' => $input['status'],
                'completed_at' => $input['status'] == Todo::STATUS_COMPLETED ? new DateTime : null,
            ]);
        }

        $todo->save();

        // index にリダイレクトする
        return Redirect::route('todos.index');

    }


    public function ajaxUpdateTitle($id)
    {
        $todo = $this->todo->find($id);
        if ($todo->user_id !== $this->user->id) {
            return Redirect::route('todos.index');
        }

        $input = Input::only(['title']);

        $rules = [
            "title" => 'required|min:3|max:255',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            // Ajax レスポンスを返す
            return \Response::json([
                'result' => 'NG',
                'errors' => $validator->errors()
            ], 400);
        }

        // titleカラムを更新する
        $todo = $todo->fill([
            'title' => $input['title'],
        ]);
        $todo->save();

        // Ajaxレスポンスを返す
        return \Response::json(['result' => 'OK'], 200);
    }

    /**
     * Todoを削除する
     * @param integer $id TodoのID
     */
    public function delete($id)
    {
        $todo = $this->todo->find($id);
        if ($todo->user_id !== $this->user->id) {
            // セッションにメッセージ 「権限ないよ！！」
            return Redirect::route('todos.index');
        }

        $todo->delete();
        return Redirect::route('todos.index');
    }

    /**
     * Todoを復元する
     * @param integer $id TodoのID
     */
    public function restore($id)
    {
        // 削除されたTodoオブジェクトを取得する
        $todo = $this->todo->onlyTrashed()->find($id);

        // データを復元する
        $todo->restore();

        // indexにリダイレクトする
        return Redirect::route('todos.index');
    }
}
