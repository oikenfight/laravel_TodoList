
{{-- 完了リスト --}}
<div id="todos-completed" class="'todos-list raw">
    <div class="colpsm-12 com-md-12">
        <h2>Completed Tasks<span class="badge">{{ count($completedTodos) }}</span></h2>

        <table class="table table-striped">
            <thead>
                <th class="title col-sm-12 col-md-8">title</th>
                <th class="completed_at col-sm-12 col-md-2">completed date</th>
                <th class="col-sm-12 col-md-2">&nbsp</th>
            </thead>
            <tbody>
                @if (count($completedTodos) > 0)
                    @foreach ($completedTodos as $todo)
                        <tr>
                            <td id="todo-{{ $todo->id }}">
                                {{ Form::open(['url' => route('todos.update', $todo->id)]) }}
                                    <input type="hidden" name="title" value="{{ $todo->title }}">
                                    <input type="hidden" name="status" value="{{ App\Todo::STATUS_INCOMPLETE }}">
                                    <button class="btn btn-link">
                                        <i class="glyphicon glyphicon-check"></i>
                                    </button>
                                    {{ $todo->title }}
                                {{ Form::close() }}
                            </td>
                            <td>
                                {{ $todo->completed_at }}
                            </td>
                            <td class="btn-group">
                                {{ Form::open(['url' => route('todos.delete', $todo->id)]) }}
                                    <button class="btn btn-danger">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Conpleted Tasks</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>