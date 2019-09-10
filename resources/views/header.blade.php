<!-- Document Header -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet"/>

        <title>Laravel</title>
    </head>
    
    <body>
    
    	<div class="jumbotron"><h1>Rest Country Search Engine</h1></div>