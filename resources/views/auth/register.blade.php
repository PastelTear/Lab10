@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row row--nogutter top-line">
	<div class="line"></div>
</div>
<div class="main">
	<div class="row">
		<div class="row--small">
			<form method="post" action="{{ route('register') }}">
				@csrf
				<h2>Форма регистрации</h2>
				@foreach ($errors->all() as $errorMessage)
					<p class="message-error">{{ $errorMessage }}</p>
				@endforeach
				<div class="form-group">
					<label>ФИО</label>
					<input type="text" name="fio" required value="{{ old('fio') }}">
				</div>
				<div class="form-group">
					<label>Email</label>
					<input type="email" name="email" required value="{{ old('email') }}">
				</div>
				<div class="form-group">
					<label>Пароль</label>
					<input type="password" name="password" required>
				</div>
				<div class="form-group">
					<label>Номер телефона</label>
					<input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67">
				</div>
				<div class="form-group">
					<button class="btn" type="submit">Отправить</button>
				</div>
				<p><a href="{{ route('login') }}">Уже есть аккаунт — войти</a></p>
			</form>
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
@endsection
