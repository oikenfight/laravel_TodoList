
{{--未完了TODOリスト --}}
<div id="todos-incomplete" class='todos-list row'>
    <div class="col-sm-12 col-md-12">

        <h2>Incomplete Tasks<span class="badge">{{ count($incompleteTodos) }}</span></h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="title col-sm-12 col-md-6">title</th>
                    <th class="created_at col-sm-6 col-md-2">created date</th>
                    <th class="updated_at col-sm-6 col-md-2">updated date</th>
                    <th class="col-sm-12 col-md-2">&nbsp</th>
                </tr>
            </thead>
            <tbody>
                @if (count($incompleteTodos) > 0)
                    @foreach ($incompleteTodos as $todo)
                        <tr>
                            <td id="todo-{{ $todo->id }}">
                                <span class="browse">
                                    {{ Form::open(['url' => route('todos.update', $todo->id)]) }}
                                        <input type="hidden" name="status" value="{{ App\Todo::STATUS_COMPLETED }}">
                                        <button type="submit" class="btn btn=link">
                                            <i class="glyphicon glyphicon-unchecked"></i>
                                        </button>
                                    {{ Form::close() }}
                                    <span name="title">
                                        {{ $todo->title }}
                                    </span>
                                    <span class="pull-right">
                                        <button type="button" name="edit" class="btn btn-link" data-id="todo-{{ $todo->id }}">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </button>
                                    </span>
                                </span>
                                <span class="edit hidden">
                                    <input type="hidden" name="status" value="'{{ App\Todo::STATUS_COMPLETED }}">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" name="cancel" class="btn btn-primary" data-id="todo-{{ $todo->id }}">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </button>
                                        </span>
                                        <input type="text" name="title" value="{{ $todo->title }}" class="form-control">
                                        <span class="input-group-btn">
                                            <button type="button" name="update" class="btn btn-primary" data-id="todo-{{ $todo->id }}"
                                                    data-url="{{ route('todos.update-title', $todo->id) }}">
                                                変更
                                            </button>
                                        </span>
                                    </div>
                                </span>
                            </td>
                            <td>
                                {{ $todo->created_at }}
                            </td>
                            <td>
                                {{ $todo->updated_at }}
                            </td>
                            <td class="btn-group">
                                {{ Form::open(['url' => route('todos.delete', $todo->id)]) }}
                                    <button type="submit" class="btn btn-danger">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="'4">No Tasks</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>