@extends('frontend.author.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3>Add New Post</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)                                        
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="">Read Time</label>
                                <input type="number" name="read_time" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="mb-3">
                                <label for="">Post Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="">Description</label>
                                <textarea id="summernote" name="desp" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="">Tags</label>
                                <select name="tag_id[]" id="select-gear" class="demo-default" multiple placeholder="Select gear...">
                                    <option value="">Select Tag...</option>
                                    <optgroup label="Climbing">
                                        @foreach ($tags as $tag)                                            
                                            <option value="{{ $tag->id }}">{{ $tag->tag_name }}</option>
                                        @endforeach
                                    </optgroup>
                                  </select>                                  
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Preview</label>
                                <input type="file" name="preview" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 m-auto">
                            <div class="mb-3 mt-5">
                                <button type="submit" class="btn btn-primary form-control">Add Post</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection