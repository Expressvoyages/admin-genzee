@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Payment</li>
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
                <h4 class="card-title">All Payments</h4>
            </div>
        </div>
        @foreach ($payments as $payment)
        <p>Transaction ID: {{ $payment->transaction_id }}</p>
        <p>User ID: {{ $payment->user_id }}</p>
        <p>Amount: {{ $payment->amount }}</p>
        <p>Status: {{ $payment->status }}</p>
        <hr>
    @endforeach
    
    {{ $payments->links() }}
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
