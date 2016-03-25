<?php
    // メタデータ、ヘルパ関数をロードする
//    require app('path').'/views/thema.php';

//    $metadata->page_title = 'Todo List';
//    $metadata->page_description = 'Laravel で作るサンプルアプリケーション';
?>

@extends('_layout.default')

{{-- インラインのCSSを記述する--}}
@section('inline-style')
    {{-- セクションをオーバーライドするために@parentを指定する --}}
@parent
    .todos-list form {
        display: inline-block;
    }
    #todos-incomplete th.title,
    #todos-completed th.title {
        padding-left: 48px;
    }
@stop

@section('content')
    <header class="jumbotron">
        <div class="container">
            <h1>Todo List</h1>
            <p>Sample application</p>
        </div>
    </header>

    <main class="container">
        {{-- 新規TODO入力フォーム --}}
        @include ('partials.todos.00_input_section')

        <hr>

        {{-- 未完了TODOリスト --}}
        @include ('partials.todos.01_incomplete_section')

        {{-- 完了TODOリスト --}}
        @include ('partials.todos.02_completed_section')

        {{-- 削除済みTODOリスト --}}
        @include ('partials.todos.03_trashed_section')
    </main>
@endsection

{{-- インラインのjavascriptを記述 --}}
@section('inline-script')
    {{-- セクションをオーバーライドするために@parentを指定 --}}
@parent
    $('.todos-list .browse button[name="edit"]').on('click', function() {
        var id = $(this).data('id');
        var browseBlock = $('#' + id + ' .browse');
        var editBlock = $('#' + id + ' .edit');

        browseBlock.addClass('hidden');
        editBlock.removeClass('hidden');
    });


    $('.todos-list .edit button[name="update"]').on('click', function(){
        var id = $(this).data('id');
        var updateUrl = $(this).data('url');

        var browseBlock = $('#' + id + ' .browse');
        var editBlock = $('#' + id + ' .edit');

        var title = $('input[name="title"]', editBlock).val();

        if (title.trim() == '') {
            browseBlock.removeClass('hidden');
            editBlock.addClass('hidden');
            return;
        }

        $.ajax({
            type: 'PUT',
            url: updateUrl,
            data: {
                title: title,
            },
            success: function() {
                $('[name="title"]', browseBlock).text(title);
                browseBlock.removeClass('hidden');
                editBlock.addClass('hidden');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (XMLRequest.status == 400) {
                    response = JSON.parse(XMLHttpRequest.responseText);
                    for (var field in response.errors) {
                        alert(response.error[field]);
                    }
                }
                else {
                    alert('タイトル更新時にエラーが発生しました');
                }
            }
        });
    });

    $('.todos-list .edit button[name="cancel"]').on('click', function() {
        var id = $(this).data('id');
        var editBlock = $('#' + id + ' .edit');

        $('input[name="title"]', editBlock).val('');
    });
@stop

