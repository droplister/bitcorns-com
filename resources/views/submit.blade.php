@extends('layouts.app')

@section('title', 'Submit Form')

@section('content')
<div class="container">
    <h1 class="display-4 mt-5 mb-5">
        Submit
    </h1>
    <div class="row mb-5">
        <div class="col-6 col-sm-4">
            <img src="https://bitcorns.com/assets/Card Template with Bitcorn Icon (300 dpi rgb).png" width="100%" />
        </div>
        <div class="col-6 col-sm-4">
            <img src="https://bitcorns.com/assets/Card Template Blank (300 dpi rgb).png" width="100%" />
        </div>
    </div>
    <p><strong>Guidelines:</strong></p>
    <p>1) Image must be 375 x 520 pixels.</p>
    <p>2) Issuance must be LOCKED.</p>
    <p>3) Asset must not be divisible.</p>
    <p>4) No NSFW content.</p>
    <p><strong>Submission Fee:</strong></p>
    <p>Burn 500 BITCORN to this address: 1BitcornSubmissionFeeAddressgL5Xg.</p>
    <p><strong>After Acceptance:</strong></p>
    <p>Send 1 of your tokens here: 1BitcornCropsMuseumAddressy149ZDr.</p>
    <p><strong>Creative Assets:</strong></p>
    <p>Here is a template and other assets that you can use, but you don't have to use them.</p>
    <p>PSD and Font: <a href="https://bitcorns.com/assets/BitcornCardTemplatePSDfonts.zip">BitcornCardTemplatePSDfonts.zip</a></p>
    <br />
    <div class="card mb-4">
        <div class="card-body">
            <iframe height="750"
                allowTransparency="true"
                frameborder="0"
                scrolling="no"
                style="width:100%;border:none"
                src="https://bitcorns.wufoo.com/embed/m1ayr7qq0x9z13t/">
            <a href="https://bitcorns.wufoo.com/forms/m1ayr7qq0x9z13t/">
                Fill out my Wufoo form!
            </a>
            </iframe>
        </div>
    </div>
</div>
@endsection