@extends('layout')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mb-5">
        <form action="{{route('article.update',$article['slug'])}}" method="post" id="createForm" role="form"
              class="form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put"/>
            <input type="hidden" name="id" value="{{$article['id']}}"/>

            <div class="row my-row" style="width: 200px">
                <img style="width: 100%" src="{{$article['image']['path'] ?? '/ArticleDefault.jpg'}}"
                     class="img-rounded img-responsive center-block" alt="Image of Hypatia">
            </div>
            <div class="form-group">
                <label for="articleTitle" class="col-sm-2 control-label">
                    {{ __("Title") }}
                </label>
                <input type="text" name="title" value="{{$article['title']}}" class="form-control" id="articleTitle"
                       placeholder="Enter title"
                       required>
            </div>
            <div class="form-group">
                <label for="mytextarea" class="control-label lbl col-12">
                    {{ __("Content") }}
                </label>
                <textarea name="content" id="mytextarea" class="create-textarea" required>
                    {!! $article['content'] !!}
                </textarea>
            </div>
            <div class="form-group">
                <label for="articleImage">Change image:</label>
                <input type="file" name="image" id="articleImage" accept="image/png, image/gif, image/jpeg"/>
            </div>
            <div class="form-group">
                <label for="files">Select files:</label>
                <input type="file" id="files" name="files[]" multiple><br><br>
            </div>


            <div class="form-group">
                <div class="form-check">
                    <input value="{{\App\Enums\ArticleStatusEnum::DRAFT}}"
                           @if($article['status'] ==\App\Enums\ArticleStatusEnum::DRAFT)
                               checked
                           @endif
                           class="form-check-input"
                           type="radio"
                           name="status" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Draft
                    </label>
                </div>
                <div class="form-check">
                    <input value="{{\App\Enums\ArticleStatusEnum::PUBLISHED}}"
                           @if($article['status'] ==\App\Enums\ArticleStatusEnum::PUBLISHED)
                               checked
                           @endif
                           class="form-check-input"
                           type="radio" name="status" id="flexRadioDefault2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Publish
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="authorName" class="col-sm-2 control-label">
                    {{ __("Author name") }}
                </label>
                <input type="text" value="{{$article['author_name']}}" name="author_name" class="form-control"
                       id="authorName" placeholder="Enter title"
                       required>
            </div>
            @if($article['files'])
                <h2>Files</h2>
                @foreach($article['files'] as $key => $file)
                    <div class="fileItem">
                        <a href="/{{$file['path']}}" target="_blank"> {{$key}} file </a>
                        <i class="delete-file" id="deleteFile" fileId="{{$file['id']}}">X</i>
                        <br/>
                    </div>
                @endforeach
            @endif

            <button type="submit" class="btn btn-info">Submit</button>

        </form>

    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: '#mytextarea',
                br_in_pre: false,
                height: 300,
                resize: false,

            });
        });


        $(document).ready(function () {
            $("#createForm").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 10,
                        maxlength: 191
                    }
                }
            });
        });

        $(document).ready(function () {
            $("#articleImage").on("change", function () {
                var fileSize = this.files[0].size;
                var maxSize = 1048576 * 5;

                if (fileSize > maxSize) {
                    alert("The size of the image should not be more than 5MB.");
                    $(this).val('');
                }
            });


            $('.delete-file').on('click', function () {
                item = $(this);
                fileId = item.attr('fileId')
                var routeUrl = '{{ route("file.destroy", ":fileId") }}';
                routeUrl = routeUrl.replace(':fileId', fileId);

                var proceed = confirm("Are you sure you want to delete?");
                if (proceed) {
                    $.ajax({
                        url: '/file/' + fileId,
                        type: "DELETE",
                        timeout: 2000,
                        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                        success: function (data, textStatus, jqXHR) {
                            window.location.reload();
                            $(this).closest('.fileItem').remove();

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("jqXHR:" + jqXHR);
                            console.log("TestStatus: " + textStatus);
                            console.log("ErrorThrown: " + errorThrown);
                        }
                    });
                } else {
                    //don't proceed
                }
            });
        });


        $("#files").on("change", function () {
            for (var i = 0; i < this.files.length; i++) {
                var file = this.files[i];
                var maxSize = 1048576 * 5;

                var selectedFiles = this.files;
                var totalFileSize = 0;
                if (selectedFiles.length > 5) {
                    alert("You are only allowed to upload a maximum of 5 files.");
                    $(this).val('');
                    return;
                }

                if (file.size > maxSize) {
                    alert("The size of the file should not be more than 5MB.");
                    $(this).val('');
                    break;
                }
            }
        });
    </script>
@endsection
