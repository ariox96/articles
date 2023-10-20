@extends('layout')

@section('content')
    <div class="container">
        <button type="button" class="btn btn-primary">write article</button>
        @if($articles->items())
        <div class="row">
                @foreach($articles as $article)
                    <div class="col-6 col-lg-6 p-2">
                        <a href="{{route('article.show',$article->slug)}}">
                            <div class="card">
                                <div class="card-image">
                                    <img
                                        src="{{$article->image?->path ?? '/ArticleDefault.jpg'}}"
                                        class="card-img-top article-image">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title" style="color: #1a202c">{{$article->title}}</h5>
                                    <p class="card-text" style="color: #1a202c">{{$article->content}}</p>
                                    <p class="card-text"><small class="text-muted">{{$article->published_at}}</small>
                                    </p>
                                    <p class="card-text"><small class="text-muted">{{auth()->user()->name}}</small></p>
                                    <p class="card-text"><small class="text-muted">{{\App\Enums\ArticleStatusEnum::getName($article->status)}}</small></p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
        </div>
            {{$articles->links('pagination::bootstrap-4')}}

            @else
                <div class="alert alert-info col-12 m-2"  role="alert">
                    <p class="text-center">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>   Get started and write your article
                    </p>
                </div>
            @endif

    </div>
@endsection

