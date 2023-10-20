@extends('layout')

@section('content')

    <div class="container">
        <div class="row">
            <div  class="col-6 col-lg-3 p-2">
                <a href="#">
                    <div class="card ">
                        <div class="card-image">
                            <img
                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Pencil_drawing_of_a_girl_in_ecstasy.jpg/800px-Pencil_drawing_of_a_girl_in_ecstasy.jpg"
                                class="card-img-top article-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="color: #1a202c">Card title</h5>
                            <p class="card-text" style="color: #1a202c">This is a longer card with supporting text below as a natural lead-in
                                to
                                additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">20232/11/12</small></p>
                            <p class="card-text"><small class="text-muted">john doe</small></p>
                        </div>
                    </div>
                </a>
            </div>
            <div  class="col-6 col-lg-3 p-2">
                <a href="#">
                    <div class="card ">
                        <div class="card-image">
                            <img
                                src="https://www.intl-spectrum.com/articles/r75/ArticleDefault.jpg?x=80x56"
                                class="card-img-top">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="color: #1a202c">Card title</h5>
                            <p class="card-text" style="color: #1a202c">This is a longer card with supporting text below as a natural lead-in
                                to
                                additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">20232/11/12</small></p>
                            <p class="card-text"><small class="text-muted">john doe</small></p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection
