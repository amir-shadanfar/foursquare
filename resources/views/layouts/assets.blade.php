<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Foursquare API </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    {{--css yield--}}
    @yield('css')
</head>

<body>
{{--body yield--}}
@yield('body')

<script src="{{asset('js/app.js')}}"></script>
{{--js yield--}}
@yield('js')
</body>