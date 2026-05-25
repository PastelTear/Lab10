@extends('layouts.app')

@section('title', 'Личный кабинет')
@section('body-class', 'dp')

@section('content')
<div class="main">
	<div class="row">
		<div class="hover"></div>
		<div class="title">Личный кабинет</div>
		<div class="row--small grid between">
			<div class="content driver-page">
				<div class="driver-page-photo">
					<img src="{{ asset('templates/img/driver-page.png') }}" alt="">
				</div>
				<div class="driver-page-name">{{ $user->fio }}</div>
				<div class="driver-page-text">
					<div class="driver-page-my">Мои мастер-классы</div>
					@if ($masterclasses->isEmpty())
						<p>У вас пока нет мастер-классов.</p>
					@else
						<table class="driver-page-table">
							<tbody>
								@foreach ($masterclasses as $masterclass)
									<tr>
										<td>
											{{ \App\Support\DateFormatter::formatRu($masterclass->mc_date) }}
											{{ \App\Support\TimeSlots::label($masterclass->start_time) }}
											<div class="cabinet-edit-link">
												<a href="{{ route('masterclass.edit', $masterclass) }}">Редактировать описание и стоимость</a>
											</div>
										</td>
										<td>
											<strong>{{ $masterclass->title }}</strong>
											<div class="cabinet-mc-stats">
												Группа: до {{ $masterclass->max_participants }} чел.,
												записано: {{ $masterclass->bookings_count }},
												цена: {{ number_format((float) $masterclass->price, 2, ',', ' ') }} ₽
											</div>
											@if ($masterclass->bookings->isEmpty())
												<p>Участников пока нет.</p>
											@else
												@foreach ($masterclass->bookings as $participantIndex => $booking)
													<p>
														{{ $participantIndex + 1 }}. {{ $booking->user->fio }}<br>
														email: {{ $booking->user->email }}<br>
														tel: {{ $booking->user->phone }}
													</p>
												@endforeach
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
				<div class="driver-page-btn-wrapper">
					<a class="driver-page-btn btn" href="{{ route('masterclass.create') }}">Добавить мастер-класс</a>
				</div>
			</div>
			@include('partials.craft-menu', ['categories' => $categories])
		</div>
	</div>
</div>
<div class="row row--nogutter">
	<div class="line"></div>
</div>
@endsection
