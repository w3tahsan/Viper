@extends('layouts.admin')

@section('content')
@can('tag_access')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>Tags List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Tag Name</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($tags as $index=>$tag)                        
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $tag->tag_name }}</td>
                        <td><a href="{{ route('tag.delete', $tag->id) }}" class="btn btn-danger">delete</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3>Add New Tags</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tag.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tag Name</label>
                        <input type="text" name="tag_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection