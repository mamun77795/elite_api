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

    <style>
        .right {
            float: right;
        }

        #exportButton {
            float: left;
        }
    </style>
    <script src="https://www.igniteui.com/js/external/FileSaver.js"></script>
    <script src="https://www.igniteui.com/js/external/Blob.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/infragistics.core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_collections.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_text.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_io.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_ui.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.documents.core_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_collectionsextended.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.excel_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_threading.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.ext_web.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.xml.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.documents.core_openxml.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2019.1/latest/js/modules/infragistics.excel_serialization_openxml.js"></script>


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
                                @php
                                $company_name= Auth::user()->name;
                                @endphp
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
          var cell = [];
          var head = [];
          var getEndUserName = true;
          var enduserName;
          var companyName = "Sunny Feed Limited";
          function getCell(){
            cell.splice(0, cell.length);
            var tableObj = document.getElementById( "exportTable" );
            var allTRs = tableObj.getElementsByTagName( "tr" );
            for ( var trCounter = 0; trCounter < allTRs.length; trCounter++ )
            {
               var tmpArr = [];
               var allTDsInTR = allTRs[ trCounter ].getElementsByTagName( "td" );
               for ( var tdCounter = 0; tdCounter < allTDsInTR.length; tdCounter++ )
               {
                tmpArr.push( allTDsInTR[ tdCounter ].innerHTML );
               }
               cell.push( tmpArr );
            }
              //console.log( arr );
          }

          function getHead(){
            head.splice(0, head.length);
            var tableObj = document.getElementById( "exportTable" );
            var allTRs = tableObj.getElementsByTagName( "tr" );
            for ( var trCounter = 0; trCounter < allTRs.length; trCounter++ )
            {
               var tmpArr = [];
               var allTDsInTR = allTRs[ trCounter ].getElementsByTagName( "th" );
               for ( var tdCounter = 0; tdCounter < allTDsInTR.length; tdCounter++ )
               {
                tmpArr.push( allTDsInTR[ tdCounter ].innerHTML );
               }
               head.push( tmpArr );
            }
              //console.log( 'A'+1 );
          }

        function createFormattingWorkbook() {

            //Declearation
            var workbook = new $.ig.excel.Workbook($.ig.excel.WorkbookFormat.excel2007);
            var sheet = workbook.worksheets().add('Sheet1');
            sheet.columns(0).setWidth(20, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            sheet.columns(1).setWidth(80, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            sheet.columns(2).setWidth(80, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            sheet.columns(6).setWidth(90, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            sheet.columns(7).setWidth(80, $.ig.excel.WorksheetColumnWidthUnit.pixel);

            //Get values of column head
            getHead();
            var cellIndex = 5;
            sheet.rows(cellIndex-1).cellFormat().font().bold(true);
            var charChanger = 0;
            var nameIndex = 0;

            for (var i in head)
            {
               for (var j in head[i])
                 {
                   if(nameIndex == 0){
                     nameIndex++;
                     continue;
                   }
                   console.log(head[i][j]);
                   sheet.getCell(String.fromCharCode('A'.charCodeAt() + charChanger)+cellIndex).value(head[i][j]);
                   charChanger++;
                   nameIndex++;
                 }
                 nameIndex = 0;
            }

            //Get values of row
            getCell();
            var charChanger = 0;
            var nameIndex = 0;
            for (var i in cell)
            {
               for (var j in cell[i])
                 {
                   if(nameIndex == 0){
                     if(getEndUserName){
                     enduserName = cell[i][j];
                     getEndUserName = false;
                   }
                     console.log('name is '+enduserName);
                     nameIndex++;
                     continue;
                   }

                   sheet.getCell(String.fromCharCode('A'.charCodeAt() + charChanger)+cellIndex).value(cell[i][j]);
                   charChanger++;
                   nameIndex++;
                 }
                 nameIndex = 0;
                 charChanger = 0;
                 cellIndex++;
            }

            //Company title
            var title = sheet.mergedCellsRegions().add(0, 2, 1, 2);
            title.value(companyName);
            title.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.left);
            title.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#ffffff'));
            title.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.CellFill.createSolidFill('#000000')));
            title.cellFormat().font().height(16 * 20);

            var name = sheet.mergedCellsRegions().add(2, 2, 2, 2);
            name.value('Name : '+ enduserName);
            name.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.left);
            name.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#ffffff'));
            name.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.CellFill.createSolidFill('#000000')));
            name.cellFormat().font().height(14 * 18);

            // var light1Fill = $.ig.excel.CellFill.createSolidFill(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            // var cells = sheet.getRegion('A1:K27').getEnumerator();
            // while (cells.moveNext()) {
            //     cells.current().cellFormat().fill(light1Fill);
            // }

            //console.log('cell index '+cellIndex);
            saveWorkbook(workbook, "Formatting.xlsx");
        }

        function saveWorkbook(workbook, name) {
            workbook.save({ type: 'blob' }, function (data) {
                saveAs(data, name);
            }, function (error) {
                alert('Error exporting: : ' + error);
            });
         }

    </script>

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

@if (Session::has('success'))
    <script>
        toastr.success("{{ Session::get('success') }}", 'Success');
    </script>
@endif

@if (Session::has('error'))
    <script>
        toastr.error("{{ Session::get('error') }}", 'Error');
    </script>
@endif


</body>
</html>
