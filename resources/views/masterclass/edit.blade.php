@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('content')
<div class="row row--nogutter top-line">
	<div class="line"></div>
</div>
<div class="main">
	<div class="row">
		<div class="row--small">
			<form method="post" action="{{ route('masterclass.update', $masterclass) }}">
				@csrf
				@method('PUT')
				<h2>Редактирование мастер-класса</h2>
				<p>
					{{ \App\Support\DateFormatter::formatRu($masterclass->mc_date) }}
					{{ \App\Support\TimeSlots::label($masterclass->start_time) }}
					— <strong>{{ $masterclass->title }}</strong>
				</p>
				@foreach ($errors->all() as $errorMessage)
					<p class="message-error">{{ $errorMessage }}</p>
				@endforeach
				<div class="form-group">
					<label>Описание мастер-класса</label>
					<textarea name="description" required rows="8">{{ old('description', $masterclass->description) }}</textarea>
				</div>
				<div class="form-group">
					<label>Стоимость (руб.)</label>
					<input type="text" name="price" inputmode="decimal" required value="{{ old('price', $masterclass->price) }}">
				</div>
				<div class="form-group">
					<button class="btn" type="submit">Сохранить</button>
					<a class="btn btn--spaced" href="{{ route('cabinet') }}">Назад</a>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
@endsection
