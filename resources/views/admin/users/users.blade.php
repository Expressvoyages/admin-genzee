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
                <div class="mt-3">
                    <form action="{{ route('users.index') }}" method="GET" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by Name or Email">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-secondary ml-2">Clear Search</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

    
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Users</h4>
                            <button id="exportrBtn" class="btn btn-primary">Export to CSV</button>
                        </div>
                        
        
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)  
                                            <th>Age</th> 
                                        @endif
                                        <th>Phone Number</th>
                                        <th>City / State</th>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)  
                                            <th>Action</th> 
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input user-checkbox" name="selectedUser" id="checkbox{{ $loop->index }}" value="{{ $user['uid']['stringValue'] }}">
                                                <label class="form-check-label" for="checkbox{{ $loop->index }}"></label>
                                            </div>
                                        </td>
                                        
                                        
                                      
                                        
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>{{ $user['email']['stringValue'] }}</td>
                                        @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)   
                                            <td>{{ $user['age']['stringValue'] }}</td> 
                                        @endif
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

<script>
  $(document).ready(function () {
    $('#exportrBtn').click(function () {
        var csv = [];
        var selectedUserIds = [];
        // Iterate over each checked checkbox and get the corresponding user data
        $('.user-checkbox:checked').each(function (index, checkbox) {
            var row = $(checkbox).closest('tr');
            var rowData = [];
            // Exclude the first column (checkbox) and last column (Action) from each row
            row.find('td:not(:first-child, :last-child)').each(function (index, column) {
                rowData.push($(column).text());
            });
            csv.push(rowData.join(','));
            selectedUserIds.push($(checkbox).val());
        });
        // If no users are selected, show an alert
        if (selectedUserIds.length === 0) {
            alert('Please select at least one user to export.');
            return;
        }
        downloadCSV(csv.join('\n'), '(SelectedUsersData).csv');
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
