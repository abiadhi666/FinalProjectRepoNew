@extends('sbadmin.master')

@section('content')
<div class="container-fluid">
    <div class="mt-3 ml-3">
        @forelse($user->questions->sortByDesc('updated_at') as $key => $question)
            <div class="card p-3">
                <div class="form-group">
                    <label for="title">
                        <h3>{{ $question->title }}</h3>
                    </label>
                    <br>
                    <label for="content">
                        <h5>
                            {!! $question->content !!}
                        </h5>
                    </label>
                    <br>
                    <label for="tags">
                        @forelse($question->tags as $tag)
                            <a href="/tag/{{ $tag->id }}/{{ $tag->name }}">
                                <button class="btn btn-primary">#{{ $tag->name }}</button>
                            </a>
                        @empty
                            <p>Tidak ada tags</p>
                        @endforelse
                    </label>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <div class="p-2">
                        <img class="direct-chat-img"
                            src="{{ asset('/adminLTE/dist/img/user7-128x128.jpg') }}"
                            alt="User Avatar" style="size: 10;margin-right:10px;">
                        <font style="font-size: 12px">{{ $question->user->name }} ( <font style="color: blue">
                                <b>{{ $question->user->reputation->point }}</b> reputation
                            </font> )
                            <br />Post :
                            {{ date('d M Y, g:i a', strtotime($question->created_at)) }}
                        </font>
                    </div>
                    <div class="p-2">
                        <a href="/upvote_question/{{ $question->id }}" type="button" class="btn btn-primary btn-sm">
                            Upvote <span class="badge badge-light">{{ $question->votes->where('upvote', 1)->count() }}</span>
                        </a>
                        <a href="/downvote_question/{{ $question->id }}" type="button" class="btn btn-danger btn-sm">
                            Downvote <span class="badge badge-light">{{ $question->votes->where('downvote', 1)->count() }}</span>
                        </a>
                    </div>
                </div>
                <div class="d-flex">
                    <a href="/question/{{ $question->id }}" class="btn btn-warning btn-sm">
                        View
                    </a>
                    <a href="/question/{{ $question->id }}/edit" class="btn btn-success btn-sm mx-1">
                        Update
                    </a>
                    <form action="/question/{{ $question->id }}/delete" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                    </form>
                </div>

            </div>
        @empty
            <tr>
                <td colspan="4" align='center'>
                    Tidak Ada Pertanyaan
                </td>
            </tr>
        @endforelse

    </div>

</div>



@endsection
