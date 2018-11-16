@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <span class="flex">
                                    <a href="/profiles/{{ $thread->creator->name }}">
                                        {{ $thread->creator->name }}
                                    </a> posted:
                                    {{ $thread->title }}
                                </span>

                                <div>
                                    @can ('update', $thread)
                                        <form action="{{ $thread->path() }}" method="POST">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            
                                            <button type="submit" class="btn btn-link">Delete Thread!</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        
                        <div class="card-body">
                            {{ $thread->body }}
                        </div>

                    </div>

                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

                    {{-- @foreach ($replies as $reply)
                        @include('threads.reply')
                    @endforeach

                    {{ $replies->links() }} --}}

                    @if (auth()->check())
                        <form method="POST" action="{{ $thread->path() . '/replies' }}">
                            @csrf
                            
                            <div class="form-group mt-3">
                                <textarea
                                class="form-control"
                                name="body"
                                id="body"
                                rows="5"
                                placeholder="Have something to say?"
                                >
                                </textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-default">
                                Post
                            </button>

                        </form>
                        @else
                        <p class="text-center">
                            Please 
                            <a href="{{ route('login') }}">
                            sign in
                            </a>
                            to participate in this discussion.
                        </p>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <p>
                                This thread was published 
                                {{ $thread->created_at->diffForHumans() }}
                                by
                                <a href="#">
                                    {{ $thread->creator->name }}
                                </a> 
                                and currently has
                                <span v-text="repliesCount"></span>
                                {{ str_plural('comment', $thread->replies_count) }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </thread-view>
@endsection
