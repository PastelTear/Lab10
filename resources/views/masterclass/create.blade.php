@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

@section('content')
<div class="row row--nogutter top-line">
	<div class="line"></div>
</div>
<div class="main">
	<div class="row">
		<div class="row--small">
			<form method="post" action="{{ route('masterclass.store') }}" id="mc_form">
				@csrf
				<h2>Форма добавления мастер-класса</h2>
				@foreach ($errors->all() as $errorMessage)
					<p class="message-error">{{ $errorMessage }}</p>
				@endforeach
				<div class="form-group">
					<label>Вид творчества</label>
					<select name="category_id" required>
						<option value="">— выберите —</option>
						@foreach ($categories as $categoryRow)
							<option value="{{ $categoryRow->id }}" @selected(old('category_id') == $categoryRow->id)>{{ $categoryRow->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label>Название мастер-класса</label>
					<input type="text" name="title" required value="{{ old('title') }}">
				</div>
				<div class="form-group">
					<label>Описание мастер-класса</label>
					<textarea name="description" required rows="8">{{ old('description') }}</textarea>
				</div>
				<div class="form-group">
					<label>Дата</label>
					<input type="date" name="mc_date" id="mc_date" required min="{{ now()->format('Y-m-d') }}" value="{{ old('mc_date') }}">
				</div>
				<div class="form-group">
					<label>Время (интервал 2 часа)</label>
					<select name="start_time" id="start_time" required>
						<option value="">— выберите —</option>
						@foreach ($timeSlots as $slotValue => $slotLabel)
							<option value="{{ $slotValue }}" @selected(old('start_time') === $slotValue)>{{ $slotLabel }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label>Количество человек в группе</label>
					<input type="number" name="max_participants" min="1" required value="{{ old('max_participants') }}">
				</div>
				<div class="form-group">
					<label>Стоимость мастер-класса (руб.)</label>
					<input type="text" name="price" inputmode="decimal" required value="{{ old('price') }}">
				</div>
				<div class="form-group">
					<button class="btn" type="submit">Отправить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
<script>
(function () {
	var dateEl = document.getElementById('mc_date');
	var sel = document.getElementById('start_time');
	if (!dateEl || !sel) return;
	function refreshBusy() {
		var d = dateEl.value;
		if (!d) return;
		fetch('{{ route('busy-slots') }}?date=' + encodeURIComponent(d), { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				var busy = data.busy || [];
				for (var i = 0; i < sel.options.length; i++) {
					var opt = sel.options[i];
					if (!opt.value) continue;
					opt.disabled = busy.indexOf(opt.value) !== -1;
				}
			}).catch(function () {});
	}
	dateEl.addEventListener('change', refreshBusy);
	dateEl.addEventListener('input', refreshBusy);
	refreshBusy();
})();
</script>
@endsection
