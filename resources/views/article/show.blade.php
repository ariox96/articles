@extends('layout')

@section('content')

    <div class="container-fluid">

        <div class="container">
            <div class="jumbotron">
                <h1 class="text-primary text-center">{{$article->title}}</h1>
                <div class="row my-row">

                    <img src="/{{$article->image?->path ?? 'ArticleDefault.jpg'}}"
                         class="img-rounded img-responsive center-block" alt="Image of Hypatia">
                </div>

                <p>
                    {!! $article->content !!}
                </p>

                @if($article->files->toArray())
                    <h2>Files</h2>
                    @foreach($article->files as $key => $file)
                        <a href="/{{$file->path}}" target="_blank"> {{$key}} file </a>
                        <br/>
                    @endforeach
                @endif
                <p class="card-text"><small class="text-muted">{{$article->published_at}}</small></p>
                <p class="card-text"><small class="text-muted">{{auth()->user()->name}}</small></p>


            </div>


        </div>  <!-- container fluid -->

@endsection

