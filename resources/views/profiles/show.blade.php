@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="page-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>            
                </div>

                <hr>

                @foreach ($activities as $date => $activity)
                    <h3 class="card">{{ $date }}</h3>

                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>


@endsection