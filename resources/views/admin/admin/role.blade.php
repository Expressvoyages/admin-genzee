@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Admin</li>
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
                <h4 class="card-title">All Administrator</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Admin Roles Description</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>{{$admin->id}}</td>
                                        <td>{{$admin->name}}</td>

                                        <td>
                                            @if($admin->user_role == 1) CEO @elseif($admin->user_role == 2) COO @elseif($admin->user_role == 3) Customer Care @elseif($admin->user_role == 4) Accountant @endif
                                        </td>
                                        <td>
                                            @if($admin->user_role == 1 || $admin->user_role == 2)
                                            <span class="badge badge-success">Have all access</span>
                                            @elseif($admin->user_role == 3)
                                            <span class="badge badge-warning">Can only see names, phone number of registered users</span>
                                            @elseif($admin->user_role == 4)
                                            <span class="badge badge-primary">Can see names, phone number and payment of registered user</span>
                                            @endif
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
