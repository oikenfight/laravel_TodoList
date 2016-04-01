<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

/**
 * App\Todo
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property string $completed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereCompletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Todo whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Todo extends Model
{
    //ソフトデリート機能をクラスに追加する
    use SoftDeletes;

    // statusカラムの取りうる値を定義
    // Mysql では enum型を使うべき？
    const STATUS_INCOMPLETE = 1; // 未完了状態
    const STATUS_COMPLETED = 2;  // 完了状態

    /**
     * このモデルで使用するデータベース名
     *
     * @var string
     */
    //protected $table = 'todos';


    // これよくわからん
    /**
     * create() / fill() で代入を許可しないDBカラム名のリスト
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * タイムスタンプ(作成日時・更新日時)カラムを有効にする
     * デフォルトではtrue
     * trueの場合、created_at, updated_atを日付型(\Carbon\Carbon)として扱う
     *
     * @var array
     */
    // public $timestamp = true;

    /**
     * 追記の日付カラム
     * デフォルトでは []
     * ここで指定されたカラムの値は、日付型(\Carbon\Carbon)で取得できる
     *
     * @@var array
     */
    protected $data = ['complete_at', 'deleted_at'];


    public static function getTodos($userId, $status)
    {
        return self::where('user_id', $userId)
            ->where('status', $status)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public static function getTrashed($userId)
    {
        return self::where('user_id', $userId)
            ->onlyTrashed()
            ->get();
    }

    public static function getTodoById($id, $userId)
    {
        return self::find($id)
            ->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
