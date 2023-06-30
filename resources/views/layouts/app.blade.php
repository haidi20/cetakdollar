<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS - KPT</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.ico') }}" type="image/x-icon">
    

    <style>

@font-face{font-display:block;font-family:bootstrap-icons;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/bootstrap-icons/bootstrap-icons.woff2?ea98e12d2d58747f9fc557577a85042e) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/bootstrap-icons/bootstrap-icons.woff?e559bf06bc84fd9525e61ead369f2a7e) format("woff")}


        @font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:300;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-ext-300-normal.woff2?bdea52a97975fe3b5a913b4103780c1b) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-300-normal.woff?7e0da6e09bc199986988c0c7498beb1c) format("woff");unicode-range:u+0460-052f,u+1c80-1c88,u+20b4,u+2de0-2dff,u+a640-a69f,u+fe2e-fe2f}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:300;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-300-normal.woff2?3cf1ea4958e9e18a6eba61a695f548f2) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-300-normal.woff?7e0da6e09bc199986988c0c7498beb1c) format("woff");unicode-range:u+0400-045f,u+0490-0491,u+04b0-04b1,u+2116}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:300;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-vietnamese-300-normal.woff2?dfa31d492c43807b27c29c50bdc1688b) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-300-normal.woff?7e0da6e09bc199986988c0c7498beb1c) format("woff");unicode-range:u+0102-0103,u+0110-0111,u+0128-0129,u+0168-0169,u+01a0-01a1,u+01af-01b0,u+1ea0-1ef9,u+20ab}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:300;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-ext-300-normal.woff2?efc4195dbb895ec1985c7be44326bd63) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-300-normal.woff?7e0da6e09bc199986988c0c7498beb1c) format("woff");unicode-range:u+0100-024f,u+0259,u+1e??,u+2020,u+20a0-20ab,u+20ad-20cf,u+2113,u+2c60-2c7f,u+a720-a7ff}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:300;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-300-normal.woff2?b910844d7a322238ec945d43a31a7050) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-300-normal.woff?7e0da6e09bc199986988c0c7498beb1c) format("woff");unicode-range:u+00??,u+0131,u+0152-0153,u+02bb-02bc,u+02c6,u+02da,u+02dc,u+2000-206f,u+2074,u+20ac,u+2122,u+2191,u+2193,u+2212,u+2215,u+feff,u+fffd}
        @font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:400;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-ext-400-normal.woff2?f21ac09511c6fa4f94633549c5716665) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-400-normal.woff?8f2fc6317388452f7ad144ea0cdb853e) format("woff");unicode-range:u+0460-052f,u+1c80-1c88,u+20b4,u+2de0-2dff,u+a640-a69f,u+fe2e-fe2f}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:400;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-400-normal.woff2?a0473b5006c28d8a449913aaab9e225d) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-400-normal.woff?8f2fc6317388452f7ad144ea0cdb853e) format("woff");unicode-range:u+0400-045f,u+0490-0491,u+04b0-04b1,u+2116}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:400;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-vietnamese-400-normal.woff2?6a8bc7acd205391fb71a9326938d6b63) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-400-normal.woff?8f2fc6317388452f7ad144ea0cdb853e) format("woff");unicode-range:u+0102-0103,u+0110-0111,u+0128-0129,u+0168-0169,u+01a0-01a1,u+01af-01b0,u+1ea0-1ef9,u+20ab}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:400;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-ext-400-normal.woff2?e8049e65c49ce687cff1bf71dfa6a5d0) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-400-normal.woff?8f2fc6317388452f7ad144ea0cdb853e) format("woff");unicode-range:u+0100-024f,u+0259,u+1e??,u+2020,u+20a0-20ab,u+20ad-20cf,u+2113,u+2c60-2c7f,u+a720-a7ff}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:400;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-400-normal.woff2?b8644b6e04ecda1cf98bbb37f17d0ef3) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-400-normal.woff?8f2fc6317388452f7ad144ea0cdb853e) format("woff");unicode-range:u+00??,u+0131,u+0152-0153,u+02bb-02bc,u+02c6,u+02da,u+02dc,u+2000-206f,u+2074,u+20ac,u+2122,u+2191,u+2193,u+2212,u+2215,u+feff,u+fffd}
        @font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:600;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-ext-600-normal.woff2?a966a96b5f86c1790d611dc2a071f533) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-600-normal.woff?c98d6b1e33b1d5275530ae9cad92dc09) format("woff");unicode-range:u+0460-052f,u+1c80-1c88,u+20b4,u+2de0-2dff,u+a640-a69f,u+fe2e-fe2f}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:600;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-600-normal.woff2?1dd32244f44e3237f333f099fbb9e7b5) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-600-normal.woff?c98d6b1e33b1d5275530ae9cad92dc09) format("woff");unicode-range:u+0400-045f,u+0490-0491,u+04b0-04b1,u+2116}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:600;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-vietnamese-600-normal.woff2?73867bf9b4b837f997c48e82ec28616a) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-600-normal.woff?c98d6b1e33b1d5275530ae9cad92dc09) format("woff");unicode-range:u+0102-0103,u+0110-0111,u+0128-0129,u+0168-0169,u+01a0-01a1,u+01af-01b0,u+1ea0-1ef9,u+20ab}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:600;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-ext-600-normal.woff2?9704ee910d46b3c17e69dce6da1b19a3) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-600-normal.woff?c98d6b1e33b1d5275530ae9cad92dc09) format("woff");unicode-range:u+0100-024f,u+0259,u+1e??,u+2020,u+20a0-20ab,u+20ad-20cf,u+2113,u+2c60-2c7f,u+a720-a7ff}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:600;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-600-normal.woff2?2b48b7fe12163661f95ab32aebeaed01) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-600-normal.woff?c98d6b1e33b1d5275530ae9cad92dc09) format("woff");unicode-range:u+00??,u+0131,u+0152-0153,u+02bb-02bc,u+02c6,u+02da,u+02dc,u+2000-206f,u+2074,u+20ac,u+2122,u+2191,u+2193,u+2212,u+2215,u+feff,u+fffd}
        @font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:700;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-ext-700-normal.woff2?6f62b8277e1be35439a6f146e35525c3) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-700-normal.woff?46a2b1a717b94f9b4b103b12d8bf7b6b) format("woff");unicode-range:u+0460-052f,u+1c80-1c88,u+20b4,u+2de0-2dff,u+a640-a69f,u+fe2e-fe2f}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:700;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-700-normal.woff2?d30bbf13744f76549350b999396205c8) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-700-normal.woff?46a2b1a717b94f9b4b103b12d8bf7b6b) format("woff");unicode-range:u+0400-045f,u+0490-0491,u+04b0-04b1,u+2116}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:700;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-vietnamese-700-normal.woff2?8862ea6993677ed0a88f2f310121476b) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-700-normal.woff?46a2b1a717b94f9b4b103b12d8bf7b6b) format("woff");unicode-range:u+0102-0103,u+0110-0111,u+0128-0129,u+0168-0169,u+01a0-01a1,u+01af-01b0,u+1ea0-1ef9,u+20ab}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:700;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-ext-700-normal.woff2?4bcdf80378aecff12c8d67cfc4164cf6) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-700-normal.woff?46a2b1a717b94f9b4b103b12d8bf7b6b) format("woff");unicode-range:u+0100-024f,u+0259,u+1e??,u+2020,u+20a0-20ab,u+20ad-20cf,u+2113,u+2c60-2c7f,u+a720-a7ff}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:700;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-700-normal.woff2?adfd120897fcd366e78e43a700ca8bfc) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-700-normal.woff?46a2b1a717b94f9b4b103b12d8bf7b6b) format("woff");unicode-range:u+00??,u+0131,u+0152-0153,u+02bb-02bc,u+02c6,u+02da,u+02dc,u+2000-206f,u+2074,u+20ac,u+2122,u+2191,u+2193,u+2212,u+2215,u+feff,u+fffd}
        @font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:800;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-ext-800-normal.woff2?7931b4451e85184334d5e5ad03c2e1b1) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-800-normal.woff?d4a1243dee08ed1aa06aa2d858a0e70c) format("woff");unicode-range:u+0460-052f,u+1c80-1c88,u+20b4,u+2de0-2dff,u+a640-a69f,u+fe2e-fe2f}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:800;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-cyrillic-800-normal.woff2?da7d3180b2807d20f628c3fef69a047b) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-800-normal.woff?d4a1243dee08ed1aa06aa2d858a0e70c) format("woff");unicode-range:u+0400-045f,u+0490-0491,u+04b0-04b1,u+2116}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:800;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-vietnamese-800-normal.woff2?1911355e3ea73b1cce95fe7de940eb72) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-800-normal.woff?d4a1243dee08ed1aa06aa2d858a0e70c) format("woff");unicode-range:u+0102-0103,u+0110-0111,u+0128-0129,u+0168-0169,u+01a0-01a1,u+01af-01b0,u+1ea0-1ef9,u+20ab}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:800;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-ext-800-normal.woff2?612cd00048b57f272c0046c7349e1c1b) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-800-normal.woff?d4a1243dee08ed1aa06aa2d858a0e70c) format("woff");unicode-range:u+0100-024f,u+0259,u+1e??,u+2020,u+20a0-20ab,u+20ad-20cf,u+2113,u+2c60-2c7f,u+a720-a7ff}@font-face{font-display:swap;font-family:Nunito;font-style:normal;font-weight:800;src:url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-latin-800-normal.woff2?de4064435bf0f21e81104a7f39c75527) format("woff2"),url({{\URL::to('/')}}/assets-mazer/fonts/vendor/@fontsource/nunito/files/nunito-all-800-normal.woff?d4a1243dee08ed1aa06aa2d858a0e70c) format("woff");unicode-range:u+00??,u+0131,u+0152-0153,u+02bb-02bc,u+02c6,u+02da,u+02dc,u+2000-206f,u+2074,u+20ac,u+2122,u+2191,u+2193,u+2212,u+2215,u+feff,u+fffd}
        .ps{-ms-overflow-style:none;overflow:hidden!important;overflow-anchor:none;touch-action:auto;-ms-touch-action:auto}.ps__rail-x{bottom:0;height:15px}.ps__rail-x,.ps__rail-y{display:none;opacity:0;position:absolute;transition:background-color .2s linear,opacity .2s linear;-webkit-transition:background-color .2s linear,opacity .2s linear}.ps__rail-y{right:0;width:15px}.ps--active-x>.ps__rail-x,.ps--active-y>.ps__rail-y{background-color:transparent;display:block}.ps--focus>.ps__rail-x,.ps--focus>.ps__rail-y,.ps--scrolling-x>.ps__rail-x,.ps--scrolling-y>.ps__rail-y,.ps:hover>.ps__rail-x,.ps:hover>.ps__rail-y{opacity:.6}.ps .ps__rail-x.ps--clicking,.ps .ps__rail-x:focus,.ps .ps__rail-x:hover,.ps .ps__rail-y.ps--clicking,.ps .ps__rail-y:focus,.ps .ps__rail-y:hover{background-color:#eee;opacity:.9}.ps__thumb-x{bottom:2px;height:6px;transition:background-color .2s linear,height .2s ease-in-out;-webkit-transition:background-color .2s linear,height .2s ease-in-out}.ps__thumb-x,.ps__thumb-y{background-color:#aaa;border-radius:6px;position:absolute}.ps__thumb-y{right:2px;transition:background-color .2s linear,width .2s ease-in-out;-webkit-transition:background-color .2s linear,width .2s ease-in-out;width:6px}.ps__rail-x.ps--clicking .ps__thumb-x,.ps__rail-x:focus>.ps__thumb-x,.ps__rail-x:hover>.ps__thumb-x{background-color:#999;height:11px}.ps__rail-y.ps--clicking .ps__thumb-y,.ps__rail-y:focus>.ps__thumb-y,.ps__rail-y:hover>.ps__thumb-y{background-color:#999;width:11px}@supports (-ms-overflow-style:none){.ps{overflow:auto!important}}@media (-ms-high-contrast:none),screen and (-ms-high-contrast:active){.ps{overflow:auto!important}}
        @charset "UTF-8";

    </style>
    <link rel="stylesheet" href="{{ asset('assets-mazer/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-mazer/css/shared/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/fontawesome.css') }}">
    <style>
        .form-group[class*="has-icon-"] .form-control-icon i,
        .form-group[class*="has-icon-"] .form-control-icon svg {
            line-height: 1 !important;
        }
    </style>
</head>

<body style="background-color: var(--bs-body-bg) !important;">
    <div id="app">
        @yield('content')
    </div>
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
</body>

</html>
