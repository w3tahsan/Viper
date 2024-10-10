@extends('layouts.admin')
@section('content')
    <div class="row">
        @can('users')
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">User List</h3>
                    </div>
                    <div class="card-body">
                        @if (session('del'))
                            <div class="alert alert-success">{{ session('del') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Photo</th>
                                    @can('@endcan')
                                        <th>Action</th>
                                    @endcan
                            </tr>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->photo == null)
                                            <img src="https://via.placeholder.com/30x30" alt="profile">
                                        @else
                                            <img src="{{ asset('uploads/user') }}/{{ $user->photo }}" width="100"
                                                alt="">
                                        @endif

                                    </td>
                                    @can('user_delete')
                                        <td>
                                            <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger">Delete</a>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('user_add')
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="text-white">Add New User</h3>
                </div>
                <div class="card-body">
                    @if (session('add_user'))
                        <div class="alert alert-success">{{ session('add_user') }}</div>
                    @endif
                    <form action="{{ route('add.user') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="mb-3" class="form-label">Name</div>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <div class="mb-3" class="form-label">Email</div>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <div class="mb-3" class="form-label">Password</div>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</div>
@endsection
