<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>@yield('title', 'ОчУмелые ручки')</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('templates/css/styles.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('templates/css/responsive.css') }}">
</head>
@if (trim($__env->yieldContent('body-class')))
<body class="{{ trim($__env->yieldContent('body-class')) }}">
@else
<body>
@endif
	<div class="header">
		<div class="row grid middle between">
			<div class="logo">
				<a href="{{ route('home') }}"><img src="{{ asset('templates/img/logo.png') }}" alt=""></a>
			</div>
			<div class="title">
				Клуб любителей творчества «ОчУмелые ручки»
			</div>
			<div class="auth">
				@auth
					@php
						$profileUrl = auth()->user()->isLeader() ? route('cabinet') : route('home');
					@endphp
					<a href="{{ $profileUrl }}" class="auth-profile">{{ auth()->user()->fio }}</a>
					<a href="{{ route('logout') }}"
					   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выход</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden" style="display:none;">
						@csrf
					</form>
				@else
					<a href="{{ route('login') }}">Вход</a> /
					<a href="{{ route('login') }}">Регистрация</a>
				@endauth
			</div>
		</div>
	</div>
	<div class="row row--nogutter">
		<div class="menu-burger">
			<div class="burger">
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
	</div>
	@if (session('ok'))
		<div class="row">
			<div class="row--small flash-message flash-message--ok">{{ session('ok') }}</div>
		</div>
	@endif
	@if (session('error'))
		<div class="row">
			<div class="row--small flash-message flash-message--error">{{ session('error') }}</div>
		</div>
	@endif
	@yield('content')
	<div class="footer">
		<div class="row">
			<div class="row--small grid between">
				<div class="address">Наш адрес: ВДНХ, 120в</div>
				<div class="tel">Тел: 89123456765</div>
				<div class="copy">(с) Copyright, 2017</div>
			</div>
		</div>
	</div>
</body>
</html>
