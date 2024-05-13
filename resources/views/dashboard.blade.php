@include('dash.head') 
@include('dash.header') 
@include('dash.nav')
<div class="page-wrapper">
    <!-- Page wrapper  -->

    <div class="container-fluid">
        <!-- Container fluid  -->

        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Hello {{ auth()->user()->name }}, welcome back</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>



        
        @if(Auth::user()->user_role != 1 && Auth::user()->user_role != 2 && Auth::user()->user_role != 3)


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Payments</h4>
                            <button id="exportBitn" class="btn btn-primary">Export to CSV</button>
                        </div>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                     
                                        <th>transaction_id</th>
                                        <th>user_id</th>
                                        <th>amount</th>
                                        <th>status</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        

        @endif

        @if(Auth::user()->user_role != 1 && Auth::user()->user_role != 2 && Auth::user()->user_role != 4)


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Users</h4>
                            <button id="exportBtn" class="btn btn-primary">Export to CSV</button>
                        </div>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Phone Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>{{ $user['city']['stringValue'] }}</td>
                                        <td>{{ $user['state']['stringValue'] }}</td>
                                        <td>{{ $user['email']['stringValue'] }}</td>
                                        <td>{{ $user['gender']['stringValue'] }}</td>
                                        <td>{{ $user['phoneNumber']['stringValue'] }}</td>
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
        

        @endif
       
        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-info">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-light">{{ $totalUsers }}</h3>
                                <h5 class="text-muted m-b-0"><a href="{{route('users.index')}}">Total Users</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                       <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-warning">
                                <i class="far fa-image"></i>
                            </div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalPhoto }}</h3>
                                <h5 class="text-muted m-b-0"><a href="{{route('fetch.images')}}">Total photos</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-primary">
                                <i class="far fa-smile"></i>
                       
                            </div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalStickers }}</h3>
                                <h5 class="text-muted m-b-0"> <a href="{{route('admin.stickers')}}">Total Stickers</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-danger"> <i class="far fa-frown"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalComplains }}</h3>
                                <h5 class="text-muted m-b-0"> <a href="{{route('report.index')}}"> Total Complains</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        
        
        <div class="row">
         <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total Users by State</h4>
                <ul class="list-group">
                    @foreach($usersByState as $state => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $state }}: {{ $count }}</span>
                            <span class="badge badge-primary badge-pill">{{ round(($count / $totalUsers) * 100, 2) }}%</span>
                            <div class="progress" style="height: 20px; width: 100%;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($count / $totalUsers) * 100 }}%;" aria-valuenow="{{ ($count / $totalUsers) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total Users by City</h4>
                <ul class="list-group">
                    @foreach($usersByCity as $city => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $city }}: {{ $count }}</span>
                            <span class="badge badge-primary badge-pill">{{ round(($count / $totalUsers) * 100, 2) }}%</span>
                            <div class="progress" style="height: 20px; width: 100%;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($count / $totalUsers) * 100 }}%;" aria-valuenow="{{ ($count / $totalUsers) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>


        
        @endif
        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4 )
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Users</h4>
                            <!--<button id="exportBtn" class="btn btn-primary">Export to CSV</button>-->
                        </div>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>{{ $user['city']['stringValue'] }}</td>
                                        <td>{{ $user['state']['stringValue'] }}</td>
                                        <td>{{ $user['email']['stringValue'] }}</td>
                                        <td>{{ $user['gender']['stringValue'] }}</td>
                                        <td>{{ $user['phoneNumber']['stringValue'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="User Actions">
                                                <a href="{{ route('users.edit', $user['uid']['stringValue']) }}" class="btn btn-info btn-md m-2">Edit</a>
                                            </div>
                                        </td>
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
        @endif
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#exportBtn').click(function () {
            var csv = [];
            var rows = $('.table tbody tr');
            rows.each(function (index, row) {
                var rowData = [];
                $(row).find('td').each(function (index, column) {
                    rowData.push($(column).text());
                });
                csv.push(rowData.join(','));
            });
            downloadCSV(csv.join('\n'), 'Customercare(Userdata).csv');
        });

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], { type: 'text/csv' });

            // Download link
            downloadLink = document.createElement('a');

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = 'none';

            // Add the link to DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();
        }
    });
</script>


        @include('dash.footer')

    </div>
</div>
