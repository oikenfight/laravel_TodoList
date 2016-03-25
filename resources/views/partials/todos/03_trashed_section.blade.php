
{{-- 削除済みTODOリスト --}}
<div id="todos-trashed" class="todos-list row">
    <div class="col-sm-12 col-md-12">
        <h2>Archive</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="title col-sm-12 col-md-6">title</th>
                    <th class="completed_at col-sm-12 col-md-2">completed date</th>
                    <th class="deleted_at col-sm-12 col-md-2">deleted date</th>
                    <th class="col-sm-12 col-md-2">&nbsp</th>
                </tr>
            </thead>
            <tbody>
                @if (count($trashedTodos) > 0)
                    @foreach($trashedTodos as $todo)
                        <tr>
                            <td id="todo-{{ $todo->id }}">
                                {{ $todo->title }}
                            </td>
                            <td>
                                @if ($todo->completed_at)
                                    {{ $todo->completed_at }}
                                @else
                                @endif
                            </td>
                            <td>
                                {{ $todo->updated_at }}
                            </td>
                            <td class="btn-group">
                                {{ Form::open(['url' => route('todos.restore', $todo->id)]) }}
                                    <button class="btn btn-success">restore</button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Deleted Tasks</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>