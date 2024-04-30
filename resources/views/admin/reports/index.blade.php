@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">report</li>
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
                <h4 class="card-title">Profile Reports (Latest reports)</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Messages</th>
                                        <th>Reason</th>
                                        <th>Reported Account</th>
                                        <th>Reporter</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaintsPaginated as $complaint)
                                    <tr>
                                        <td>{{ $complaint['messages']['stringValue'] }}</td>
                                        <td>{{ $complaint['reason']['stringValue'] }}</td>
                                        <td>{{ $complaint['reported account']['stringValue'] }}</td>
                                        <td>{{ $complaint['reporter']['stringValue'] }}</td>
                                        <td>{{ $complaint['timestamp']['stringValue'] }}</td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $complaintsPaginated->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
