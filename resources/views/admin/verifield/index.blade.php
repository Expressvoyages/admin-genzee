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
                    <li class="breadcrumb-item active">Gifts</li>
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
                <h4 class="card-title">Verifield Users</h4>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Gifts</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                      
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>age</th>
                                        <th>phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usersData as $user)
                                    <tr>
                                        <td>{{ $user['name'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td>{{ $user['age'] }}</td>
                                        <td>{{ $user['phoneNumber'] }}</td>
                                
                                        <td>
                                          <div class="btn-group" role="group" aria-label="User Actions">
                                            <a href="{{ route('verifield.edit', $user['uid']) }}" class="btn btn-primary btn-sm m-2">Edit</a>
                                            <form action="{{ route('verifield.destroy', $user['uid']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
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