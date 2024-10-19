@extends('layout')

@section('content')
    <div class="container">
        <a href="{{route('article.create')}}" target="_blank" class="btn btn-info" role="button">Write Article</a>

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
                                    <div class="card-text" style="color: #1a202c">{!!$article->content!!}</div>
                                    <p class="card-text"><small class="text-muted">{{$article->published_at}}</small>
                                    </p>
                                    <p class="card-text"><small class="text-muted">{{$article->author_name}}</small></p>
                                    <p class="card-text"><small
                                            class="text-muted">{{$article->status->label()}}</small>
                                    </p>

                                </div>
                            </div>
                        </a>
                        <div class="m-2">
                            <a class="btn btn-success" href="{{route('article.edit',$article->slug)}}" target="_blank"
                               role="button">Edit</a>
                            <button  articleSlug="{{$article->slug}}" type="button"
                                    class="btn btn-danger deleteArticle">Delete
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
            {{$articles->links('pagination::bootstrap-4')}}

        @else
            <div class="alert alert-info col-12 m-2" role="alert">
                <p class="text-center">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Get started and write your article
                </p>
            </div>
        @endif

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.deleteArticle').on('click', function () {
                var articleSlug = $(this).attr('articleSlug');
                var routeUrl = '{{ route("article.destroy", ":articleSlug") }}';
                routeUrl = routeUrl.replace(':articleSlug', articleSlug);

                var proceed = confirm("Are you sure you want to delete?");
                if (proceed) {
                    $.ajax({
                        url: routeUrl,
                        type: "DELETE",
                        timeout: 2000,
                        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                        success: function (data, textStatus, jqXHR) {
                            window.location.reload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        }
                    });
                } else {
                    //don't proceed
                }
            });
        });

    </script>
@endsection

