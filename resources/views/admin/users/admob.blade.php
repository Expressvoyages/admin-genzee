@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Advert Settings</li>
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

                        <div class="card-body text-left"> <!-- Added text-left class -->
                            <h4 class="card-title">How to Update Ads</h4>
                            <p class="card-text">Please follow these steps carefully to update the ads:</p>
                            <ol class="card-text">
                                <li>Ensure you have the correct link and photo URL for the ad.</li>
                                <li>Enter the new link and photo URL in the respective fields below.</li>
                                <li>Click the "Update Ads" button to save the changes.</li>
                            </ol>
                        </div>
                
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Advert Info</h4>
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

                        <form method="post" action="{{ route('advert.update') }}">
                            @csrf

                            <div class="form-group">
                                @foreach($advertsDetails as $fieldName => $fieldValue)
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

 @include('dash.footer')
</div>
