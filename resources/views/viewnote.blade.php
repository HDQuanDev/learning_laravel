@extends('components.main')
@section('title')
    {{ $note->title }}
@endsection
@section('content')
    <br>
    <h2>
        <center>{{ $note->title }}<br>
            <div style="font-size: 13px;" class="text-muted">View: {{ $note->view }} | Create At: {{ $note->created_at }} |
                Update At: {{ $note->updated_at }}</div>
    </h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                <pre>{!! $note->content !!}</pre>

                <footer class="blockquote-footer">Được viết bởi <cite title="Source Title">{{ $user->name }}</cite></footer>

            </blockquote>
        </div>
    </div>
@endsection
