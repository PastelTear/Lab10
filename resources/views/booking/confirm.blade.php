@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="row row--nogutter top-line">
	<div class="line"></div>
</div>
<div class="main">
	<div class="row">
		<div class="row--small">
			<h2>Подтверждение записи</h2>
			<p>Проверьте данные и подтвердите запись на мастер-класс.</p>
			<ul class="confirm-details">
				<li><strong>Ваше ФИО:</strong> {{ auth()->user()->fio }}</li>
				<li><strong>Вид творчества:</strong> {{ $masterclass->category->name }}</li>
				<li><strong>Ведущий:</strong> {{ $masterclass->leader->fio }}</li>
				<li><strong>Мастер-класс:</strong> {{ $masterclass->title }}</li>
				<li><strong>Дата и время:</strong>
					{{ \App\Support\DateFormatter::formatRu($masterclass->mc_date) }},
					{{ \App\Support\TimeSlots::label($masterclass->start_time) }}
				</li>
				<li><strong>Стоимость:</strong> {{ number_format((float) $masterclass->price, 2, ',', ' ') }} ₽</li>
			</ul>
			<form method="post" action="{{ route('book.store', $masterclass) }}" class="confirm-form">
				@csrf
				<button class="btn" type="submit" name="action" value="confirm">Подтвердить</button>
				<button class="btn" type="submit" name="action" value="cancel">Отмена</button>
			</form>
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
@endsection
