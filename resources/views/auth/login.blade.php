@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row row--nogutter top-line">
	<div class="line"></div>
</div>
<div class="main">
	<div class="row">
		<div class="row--small">
			<form method="post" action="{{ route('login') }}">
				@csrf
				<h2>Вход</h2>
				@if ($errors->any())
					<p class="message-error--emphasis">{{ $errors->first() }}</p>
				@endif
				<div class="form-group">
					<label>E-mail</label>
					<input type="email" name="email" required value="{{ old('email') }}">
				</div>
				<div class="form-group">
					<label>Пароль</label>
					<input type="password" name="password" required>
				</div>
				<div class="form-group">
					<button class="btn" type="submit">Войти</button>
				</div>
				<p><a href="{{ route('register') }}">Форма регистрации</a></p>
			</form>
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
@endsection
