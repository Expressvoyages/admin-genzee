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
                                <i class="ti-user"></i>
                            </div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-light">{{ $totalUsers }}</h3>
                                <h5 class="text-muted m-b-0">Total Users</h5>
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
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalPhoto }}</h3>
                                <h5 class="text-muted m-b-0">Total photos</h5>
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
                            <div class="round round-lg align-self-center round-primary"><i class="ti-comment-alt"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalStickers }}</h3>
                                <h5 class="text-muted m-b-0">Total Stickers</h5>
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
                            <div class="round round-lg align-self-center round-danger"><i class="ti-comments"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $totalComplains }}</h3>
                                <h5 class="text-muted m-b-0"> Total Complains</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        @endif
        @if(Auth::user()->user_role != 3 )
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Full Statistics</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th class="text-left">Name</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">Accounts</td>
                                        <td>{{ $totalUsers }}</td>
                                    </tr>
                                   
                           
                                    <tr>
                                        <td class="text-left">Total photos</td>
                                        <td>{{ $totalPhoto }}</td>
                                    </tr>
                                 

                                    <tr>
                                        <td class="text-left">Total gifts</td>
                                        <td>{{ $totalUsers }}</td>
                                    </tr>
                                 
                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">users</h4>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">User</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 50px;">
                                            <a href="/admin/profile?id=3276">
                                                <span class="round" style="background-size: cover; background-image: url(/img/profile_default_photo.png);"></span>
                                            </a>
                                        </td>
                                        <td>
                                            <h6><a href="#"></a></h6>
                                          
                                        </td>
                                        <td>
                                            <h6></h6>
                                        </td>
                                        <td>
                                            <h6></h6>
                                        </td>
                                        <td>
                                            <h6></h6>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)   <td><a href="" class="btn btn-info">View account</a></td>   @endif
                                    </tr>

    
                                </tbody>
                            </table>
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
