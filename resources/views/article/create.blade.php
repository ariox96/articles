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
        <form action="{{route('article.store')}}" method="POST" id="createForm" role="form" class="form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="articleTitle" class="col-sm-2 control-label">                   {{ __("Title") }}
                </label>
                <input type="text" name="title" class="form-control" id="articleTitle" placeholder="Enter title"
                       required>
            </div>
            <div class="form-group">
                <label for="mytextarea" class="control-label lbl col-12">
                    {{ __("Content") }}
                </label>
                <textarea name="content" id="mytextarea" class="create-textarea" required></textarea>
            </div>
            <div class="form-group">
                <label for="articleImage">Select image:</label>
                <input type="file" name="image" id="articleImage" accept="image/png, image/gif, image/jpeg"/>
            </div>
            <div class="form-group">
                <label for="files">Select files:</label>
                <input type="file" id="files" name="files[]" multiple><br><br>
            </div>


            <div class="form-group">
                <div class="form-check">
                    <input value="{{\App\Enums\ArticleStatusEnum::STATUS_DRAFT}}" class="form-check-input" type="radio"
                           name="status" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Draft
                    </label>
                </div>
                <div class="form-check">
                    <input value="{{\App\Enums\ArticleStatusEnum::STATUS_PUBLISHED}}" class="form-check-input"
                           type="radio" name="status" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Publish
                    </label>
                </div>
            </div>

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
