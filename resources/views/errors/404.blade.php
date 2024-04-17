<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>404 page | eTracker. </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
</head>

<body class="error-page">
<div class="error-page">
    <div class="col-md-12 ">
        <div class="col-md-12 partition-blue no-padding">
            <div class="error-main">
                <div class="error-logo">
                    <img src="{{ asset('assets/images/etracker_adminpanel_logo.png') }}" height="100" width="280" alt="Logo">
                </div>
                <div class="col-md-12 big-font">404</div>
                <div class="col-md-12 big-text">Your requested page are not found !!!</div>

                <div class="button">
                    <a href="{{ url('home')}}" class="btn btn-primary">Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
