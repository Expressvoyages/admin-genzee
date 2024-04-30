@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">AdMob Settings</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <h4 class="card-title">Warning!</h4>
                        <p class="card-text">Ads settings here are delicate Please dont Touch without proper understanding of the setting</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Admob Info</h4>
                        {{--
                        <h6 class="card-subtitle">
                            How to get banner_ad_unit_id from AdMob:
                            <a href="https://raccoonsquare.com/help/how_to_get_banner_ad_unit_id_from_admob/" target="_blank">https://raccoonsquare.com/help/how_to_get_banner_ad_unit_id_from_admob/</a>
                        </h6>
                        <h6 class="card-subtitle">
                            How to get ad_unit_id for Rewarded Ads from Admob:
                            <a href="https://raccoonsquare.com/help/how_to_get_rewarded_ad_unit_id_from_admob/" target="_blank">https://raccoonsquare.com/help/how_to_get_rewarded_ad_unit_id_from_admob/</a>
                        </h6>
                        <h6 class="card-subtitle">
                            How to create Interstitial ad block you can read here:
                            <a href="https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/" target="_blank">https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/</a>
                        </h6>
                        --}}

                        <form method="post" action="{{ route('admob.update') }}">
                            @csrf

                            <div class="form-group">
                                @foreach($admobDetails as $fieldName => $fieldValue)
                                <label for="{{ $fieldName }}">{{ ucfirst($fieldName) }}:</label>
                                <input type="text" class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}" value="{{ $fieldValue }}" />
                                @endforeach
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->

    {{--
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card">
                        <div class="card-header bg-info text-white">Admob</div>
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="container">
                        <h1>Edit Admob Details</h1>
                        <form method="post" action="{{ route('admob.update') }}">
                            @csrf
                            <div class="form-group">
                                @foreach($admobDetails as $fieldName => $fieldValue)
                                <label for="{{ $fieldName }}">{{ ucfirst($fieldName) }}:</label>
                                <input type="text" class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}" value="{{ $fieldValue }}" />
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Update Admob</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}
 @include('dash.footer')
</div>
