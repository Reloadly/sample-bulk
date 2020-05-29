@extends('dashboard.layout.app')

@section('body-class','2-column')
@section('page-name','Settings')

@push('css')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/css/pages/dropzone.min.css">
    <link rel="stylesheet" type="text/css" href="/css/pages/dropzone.css">
    <style>
        .dropzone{
            min-height: 200px;
        }
        .dropzone .dz-default.dz-message{
            width: 100%;
            margin-left: 0;
            left: 0;
            top: calc(50% - 37.5px);
            margin-top: 0;
            height: 75px;
        }
        .dropzone .dz-default.dz-message span{
            display: block;
            font-size: 70%;
        }
        .dropzone .dz-message::before{
            font-size: 35px;
            top: 30px;
        }
        .dropzone .dz-preview {
            margin: 0;
        }
        .dropzone .dz-preview .dz-details img{

        }
        .dropzone .dz-preview .dz-remove{
            background: none;
            border: none;
            margin: 0;
            font-size: small;
        }
        .custom-control.custom-switch p{
            color: rgba(34, 41, 47, 0.4) !important;
            -webkit-transition: all 0.25s ease-in-out;
            transition: all 0.25s ease-in-out;
            opacity: 1;
            padding: 0.25rem 0;
            font-size: 0.7rem;
            top: -22px;
            left: -5px;
            position: absolute;
            display: block;
            pointer-events: none;
            cursor: text;
            margin-bottom: 0;
        }
        .custom-control.custom-switch .custom-control-label{
            margin-top: 0.4rem;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header justify-content-center">
            <h4 class="card-title"><i class="feather icon-users"></i> Personal Settings</h4>
        </div>
        <div class="card-content pt-2">
            <div class="card-body">
                <form class="form" action="/settings" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="text" id="first-name-floating-icon" class="form-control" placeholder="Full Name" name="name" value="{{ @Auth::user()['name'] }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-user"></i>
                                    </div>
                                    <label for="first-name-floating-icon">Full Name</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="email" id="email-id-floating-icon" class="form-control" name="email" placeholder="Email" value="{{ @Auth::user()['email'] }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-mail"></i>
                                    </div>
                                    <label for="email-id-floating-icon">Email</label>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="password" id="password-floating-icon" class="form-control" name="password" placeholder="Password">
                                    <div class="form-control-position">
                                        <i class="feather icon-lock"></i>
                                    </div>
                                    <label for="password-floating-icon">Password</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="password" id="confirm-password-floating-icon" class="form-control" name="confirm-password" placeholder="Confirm Password">
                                    <div class="form-control-position">
                                        <i class="feather icon-lock"></i>
                                    </div>
                                    <label for="confirm-password-floating-icon">Confirm Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="text" id="api-key-floating-icon" class="form-control" placeholder="Reloadly Api Key" name="reloadly_api_key" value="{{ @App\System::me()['reloadly_api_key'] }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-sliders"></i>
                                    </div>
                                    <label for="api-key-floating-icon">Reloadly Api Key</label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-label-group position-relative has-icon-left">
                                    <input type="text" id="api-secret-floating-icon" class="form-control" placeholder="Reloadly Api Secret" name="reloadly_api_secret" value="{{ @App\System::me()['reloadly_api_secret'] }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-star"></i>
                                    </div>
                                    <label for="api-secret-floating-icon">Reloadly Api Secret</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="custom-control custom-switch custom-switch-primary switch-md">
                                    <p>Api Mode</p>
                                    <input type="checkbox" class="custom-control-input" id="api-mode-switch" name="reloadly_api_mode" {{ @App\System::me()['reloadly_api_mode']=='LIVE'?'checked':'' }}>
                                    <label class="custom-control-label" for="api-mode-switch">
                                        <span class="switch-text-left">LIVE</span>
                                        <span class="switch-text-right">TEST</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 col-12">
                                <div class="dropzone text-center" data-type="full" data-src="{{ \App\System::me()['full_logo'] }}">
                                    <div class="dz-default dz-message"><span>Drop Full Logo Image to upload</span></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="dropzone text-center" data-type="icon" data-src="{{ \App\System::me()['icon_logo'] }}">
                                    <div class="dz-default dz-message"><span>Drop Icon Image to upload</span></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="dropzone text-center" data-type="text" data-src="{{ \App\System::me()['text_logo'] }}">
                                    <div class="dz-default dz-message"><span>Drop Text Logo to upload</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Submit <i class="fa fa-spinner fa-spin d-none"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/js/general.js"></script>
    <script src="/js/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        $("div.dropzone").each(function () {
            var elem = $(this);
            $(this).dropzone({
                paramName: "image",
                url: '/settings/logo/upload',
                params: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'type': elem.attr('data-type')
                },
                maxFiles: 1,
                uploadMultiple: false,
                addRemoveLinks: true,
                acceptedFiles: 'image/jpeg,image/png,image/*,.svg,.png,.jpg,.jpeg',
                init: function () {
                    var mockFile = {
                        name: '',
                        size: Math.round(1024 + Math.random()*10240),
                        type: 'image/jpeg',
                        accepted: true
                    };
                    this.files.push(mockFile);
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, elem.attr('data-src'));
                    this.emit("complete", mockFile);
                    this.on("maxfilesexceeded", function (file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    });
                    this.on("success",function (file,response) {
                        if (response.message){
                            toastr.success(response.message);
                        }
                    });
                    this.on("error",function (file,response) {
                        $.each(response, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value, 'Error');
                                });
                            }
                        });
                    })
                }
            });
        });
    </script>
@endpush
