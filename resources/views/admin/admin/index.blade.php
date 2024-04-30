@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Gifts</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body collapse show">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Admin</h4>

                        <form class="form-material m-t-40" method="post" action="{{ route('admins.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required />
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required />
                            </div>
                            <div class="form-group">
                                <label for="user_role">User Role:</label>
                                <select class="form-control" id="user_role" name="user_role" required>
                                    <option value="1">CEO</option>
                                    <option value="2">COO</option>
                                    <option value="3">Customer Care</option>
                                    <option value="4">Accountant</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Add Admin</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Admin list</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th class="text-left">Id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>{{$admin->id}}</td>
                                        <td>{{$admin->name}}</td>
                                        <td>{{$admin->email}}</td>
                                        <td>
                                            @if($admin->user_role == 1) CEO @elseif($admin->user_role == 2) COO @elseif($admin->user_role == 3) Customer Care @elseif($admin->user_role == 4) Accountant @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="User Actions">
                                                <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-primary btn-sm m-2">Edit</a>
                                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm m-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
