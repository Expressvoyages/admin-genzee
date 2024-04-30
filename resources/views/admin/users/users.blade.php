@include('dash.head')
@include('dash.nav')
@include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">All users</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body collapse show">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <h4 class="card-title">All User</h4>
            </div>
        </div>

    
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">All Users</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)  <th>age</th> @endif
                                        <th>phone Number</th>
                                        <th>City / State</th>
                                   
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)  <th>Action</th> @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>{{ $user['email']['stringValue'] }}</td>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)   <td>{{ $user['age']['stringValue'] }}</td> @endif
                                        <td>{{ $user['phoneNumber']['stringValue'] }}</td>
                                        <td>{{ $user['city']['stringValue'] }} - {{ $user['state']['stringValue'] }}</td>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                                        <td>
                                            <div class="btn-group" role="group" aria-label="User Actions">
                                                <a href="{{ route('users.edit', $user['uid']['stringValue']) }}" class="btn btn-primary btn-sm m-2">Edit</a>
                                                <form action="{{ route('users.destroy', $user['uid']['stringValue']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm m-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-center">
                        
                            {{ $usersPaginated->links('vendor.pagination.bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>


@include('dash.footer')

