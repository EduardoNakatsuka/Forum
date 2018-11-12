@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>
                            Member since 
                            {{ $profileUser->created_at->diffForHumans() }}
                        </small>
                    </h1>
                </div>
                @foreach ($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <span class="flex">
                                    <a href="#">
                                        {{ $thread->creator->name }}
                                    </a>
                                    posted:
                                    {{ $thread->title }}
                                </span>
                            
                                <span>
                                    {{ $thread->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                @endforeach
                {{ $threads->links() }}
                
            </div>
        </div>
    </div>

@endsection