
{{-- 新規TODO入力欄 --}}
<div class="row">
    <div class="col-sm-12 col-md-6">
        {{ Form::open(['url' => route('todos.store'), 'method' => 'POST']) }}
            <div class="form-group {{ (Session::has('errors') ? 'has-error' : '')}}">
                <div class="input-group">
                    <input type="text" name="title" value="{{ Input::old('title') }}" placeholder="what is todo?" class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">
                            <i class="glyphicon glyphicon-plus">追加</i>
                        </button>
                    </span>
                </div>
                @if (Session::has('errors'))
                    <p class="help-block">{{ Session::get('errors')->first('title') }}</p>
                @endif
            </div>
        {{ Form::close() }}
    </div>
</div>
