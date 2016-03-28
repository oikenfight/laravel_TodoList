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
    public function __construct(Todo $todo, \App\User $user)
    {
        $this->todo = $todo;
        $this->user = $user;
    }

    /**
     * Todoリストページを表示する
     * @param Request $request
     * @return array
     */
    public function index()
    {
        $user = \Auth::user();

//        // 未完了リストを取得
//        $incompleteTodos = Todo::where('user_id', $user['id'])->whereStatus(Todo::STATUS_INCOMPLETE)->orderBy('updated_at', 'desc')->get();
//        // 完了リストを取得する
//        $completedTodos = Todo::where('user_id', $user['id'])->whereStatus(Todo::STATUS_COMPLETED)->orderBy('completed_at', 'desc')->get();
//        // 削除済みリストを取得する
//        $trashedTodos = Todo::where('user_id', $user['id'])->onlyTrashed()->get();

        $incompleteTodos = Todo::getTodos($user['id'], Todo::STATUS_INCOMPLETE);
        $completedTodos = Todo::getTodos($user['id'], Todo::STATUS_COMPLETED);
        $trashedTodos = Todo::getTrashed($user['id']);


        // viewを生成する
        // MEMO 引数のための配列を生成するとき, complete()関数を使っても良い
        return view('todos.index', [
            'incompleteTodos' => $incompleteTodos,
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
        $user = \Auth::user();

        // バリデーションルールの定義
        $rules = [
            'title' => 'required|min:3|max:255', // titleは3文字以上255文字以下
        ];

        // フォームの入力データを項目名を指定して追加する
        $input = Input::only(['title']);

        // バリデーターを生成する
        $validator = Validator::make($input, $rules);

        // バリデーションを行う
        if ($validator->fails()) {
            // バリデーションに失敗したら、バリデーションのエラー情報とフォームの入力値を追加してリストページにリダイレクトする
            return Redirect::route('todos.index')->withErrors($validator->errors())->withInput();
        }

        // Todoデータを作成する
        Todo::create([
            'title' => $input['title'],
            'status' => Todo::STATUS_INCOMPLETE,
            'user_id' => $user['id'],
        ]);

        // index にリダイレクトする
        return Redirect::route('todos.index');
    }


    public function update($id)
    {
        $user = \Auth::user();
        $todo = Todo::find($id);

        // バリデーションルールの定義
        $rules = [
            "title" => 'requred|min:3|max:255',
            "status" => ['required', 'numeric', 'min:1', 'max:2'],
            'dummy' => '', // ルールを指定しないとオプション扱いにできる
        ];

        // 入力データを取得する
        $input = Input::only(array_keys($rules));

        // バリデーションを実行する
        $validator = Validator::make($input, $rules);
        if ($validator->failed()) {
            return Redirect::route('todos.index')->withError($validator)->withInput();
        }

        // title が指定されていたら
        if ($input['title'] !== null) {
            // titleカラムを更新する
            $todo->fill([
                'title' => $input['title'],
            ]);
        }
        // statusが指定されていたら
        if ($input['status'] !== null) {
            // statusとcompleted_atカラムを更新する
            $todo->fill([
                'status' => $input['status'],
                'completed_at' => $input['status'] == Todo::STATUS_COMPLETED ? new DateTime : null,
            ]);
        }

        // データを更新する
        $todo->save();

        // index にリダイレクトする
        return Redirect::route('todos.index');
    }


    public function ajaxUpdateTitle($id)
    {
        // Todoオブジェクトを所得する
        $todo = Todo::find($id);
        // バリデーションルールの定義
        $rules = [
            "title" => 'requred|min:3|max:255',
        ];
        // 入力データを取得する
        $input = Input::only(['title']);
        // バリデーションを実行する
        $validator = Validator::make($input, $rules);
        if ($validator->failed()) {
            // Ajax レスポンスを返す
            return Response::json([
                'result' => 'NG',
                'errors' => $validator->errors()
            , 400]);
        }

        // titleカラムを更新する
        $todo->fill([
            'title' => $input['title'],
        ]);
        // データを更新する
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
        // Todoオブジェクトを取得する
        $todo = Todo::find($id);
        // データを削除する
        $todo->delete();

        // indexにリダイレクトする
        return Redirect::route('todos.index');
    }

    /**
     * Todoを復元する
     * @param integer $id TodoのID
     */
    public function restore($id)
    {
        // 削除されたTodoオブジェクトを取得する
        $todo = Todo::onlyTrashed()->find($id);

        // データを復元する
        $todo->restore();

        // indexにリダイレクトする
        return Redirect::route('todos.index');
    }
}
