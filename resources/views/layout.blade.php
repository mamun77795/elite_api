<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ETRACKER DASHBOARD</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <script src="{{URL::asset('js/html5shiv.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('js/respond.min.js')}}" type="text/javascript"></script>
    <link href="{{URL::asset('css/styles/black.css')}}" rel="stylesheet" type="text/css" id="colorscheme"/>
    <link href="{{URL::asset('css/panel.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('css/metisMenu.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{URL::asset('css/animate.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('css/only_dashboard.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('css/jquery.colorpickersliders.css')}}"/>
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{URL::asset('css/jquery.dataTables.min.css')}}"/>
    <script src="{{URL::asset('js/jquery.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('js/moment.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{URL::asset('css/buttons.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('css/advbuttons.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-timepicker.min.css')}}"/>
    <link rel="icon" sizes="16x16" href="{{URL::asset('asset/img/favicon.png')}}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link href="{{URL::asset('css/user_profile.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('css/jasny-bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.13/css/tableexport.min.css" />
    {{---------------for calender--------------}}

    {{---------------for calender--------------}}
    @yield('raindrops-header')
    <style type="text/css">
        .gallery {
            display: inline-block;
            margin-top: 20px;
        }

        .close-icon {
            /*border-radius: 50%;*/
            position: absolute;
            right: 5px;
            top: -7px;
            padding: 0px 5px;
        }

        .form-image-upload {
            background: #e8e8e8 none repeat scroll 0 0;
            padding: 15px;
        }

        .styleright{
            display: flex!important;
            justify-content: flex-end!important;;
        }
    </style>
    @php
      $urlSegment   = basename(Request::url());

    @endphp
    <script type="text/javascript">
      $(document).ready(function() {

        $('#searchResult').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                'excel','pdf'
            ]
            // buttons: [
            //     {
            //         extend: 'excel',
            //         title: 'Search Result'
            //     }
            // ]
        } );
      } );
    </script>

    <style>
      div.scrollmenu {
        background-color: #FFFFFF;
        overflow: auto;
        white-space: nowrap;
      }

      div.scrollmenu a {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px;
        text-decoration: none;
      }

      div.scrollmenu a:hover {
        background-color: #777;
      }
    </style>

    {{-------------------for photo------------------------}}
</head>
<body class="skin-josh">
<header class="header">
    <a href="#" class="logo">
        <img src="{{ asset('assets/images/etracker_adminpanel_logo.png') }}" alt="Logo">
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <div class="riot">
                            <div>
                                {{ Auth::user()->name }}
                                <span>
                                    <i class="caret"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->

                            <!-- <img src="{{url('/')}}/storage/{{$uid=Auth::user()->picture}}" class="img-responsive img-circle" alt="User"> -->


                        <!-- Menu Body -->
                        <!-- <li>
                            <div class="hide">{{$uid=Auth::user()->id}}</div>
                            <a href="{{url('myprofile/'.$uid)}}">
                                <i class="fa fa-user"></i>
                                My Profile
                            </a>

                        </li> -->
                        <!-- Menu Footer-->
                        <li class="user-footer">


                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    @include('left-manu')
    <aside class="right-side ">
        <!-- Main content -->
        {{--<section class="content-header">--}}
            {{--<h1>{{ $customTitle or 'DASHBOARD' }}</h1>--}}
        {{--</section>--}}
        <section class="content">
            @yield('content')
            @yield('raindrops')
        </section>
    </aside>
</div>
{{-------------------------------photo upload----------------}}

<script src="{{URL::asset('js/jquery.ui.widget.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/tmpl.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/load-image.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload-process.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload-image.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload-ui.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/main.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/canvas-to-blob.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.blueimp-gallery.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.iframe-transport.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload-audio.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.fileupload-validate.js')}}" type="text/javascript"></script>
{{-------------------------------photo----------------}}
<script src="{{URL::asset('js/josh.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/metisMenu.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jasny-bootstrap.js')}}" type="text/javascript"></script>
{{--<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>--}}
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}
@yield('raindrops-footer')

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

@if (Session::has('warning'))
    <script>
        toastr.warning("{{ Session::get('warning') }}", 'Warning');
    </script>
@endif

@if (Session::has('message'))
    <script>
        toastr.info("{{ Session::get('message') }}", 'Info');
    </script>
@elseif (Session::has('news_feed_message'))
    <div class="alert alert-info">
        {!! Session::get('news_feed_message') !!}
        <a href="#" onclick="hideMessage()" class="pull-right">{{ trans('texts.hide') }}</a>
    </div>
@endif

<!-- @if (Session::has('success'))
    <script>
        toastr.success("{{ Session::get('success') }}", 'Success');
    </script>
@endif -->

@if (Session::has('error'))
    <script>
        toastr.error("{{ Session::get('error') }}", 'Error');
    </script>
@endif
{{-----------------for calender------------------}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<!-- <script src="https://raw.githubusercontent.com/clarketm/FileSaver.js/master/FileSaver.min.js"></script> -->
<script src="{{URL::asset('js/xls.core.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/FileSaver.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.13/js/tableexport.min.js"></script>
{{-------------------------exit----------------}}
@stack('scripts')

<script>
    $(function(){
        $(document).on('mouseover','.dt-button',function(){
            document.title = '<?= !empty($exportTitle)?$exportTitle: isset($title)?$title:'ETRACKER' ?>';
        })

        /* Defaults */
        $("#exportTable").tableExport({
            headings: true,                    // (Boolean), display table headings (th/td elements) in the <thead>
            footers: true,                     // (Boolean), display table footers (th/td elements) in the <tfoot>
            formats: ["xlsx","csv"],    // (String[]), filetypes for the export
            fileName: "id",                    // (id, String), filename for the downloaded file
            bootstrap: true,                   // (Boolean), style buttons using bootstrap
            position: "bottom",                 // (top, bottom), position of the caption element relative to table
            ignoreRows: null,                  // (Number, Number[]), row indices to exclude from the exported file(s)
            ignoreCols: null,                  // (Number, Number[]), column indices to exclude from the exported file(s)
            ignoreCSS: ".tableexport-ignore",  // (selector, selector[]), selector(s) to exclude from the exported file(s)
            emptyCSS: ".tableexport-empty",    // (selector, selector[]), selector(s) to replace cells with an empty string in the exported file(s)
            trimWhitespace: false              // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)
        });
    })
</script>
</body>
</html>
