@extends('layouts.app')

@section('title', 'Главная — ОчУмелые ручки')

@section('content')
<div class="main">
	<div class="row">
		<div class="hover"></div>
		<div class="title">Добро пожаловать</div>
		<div class="row--small grid between">
			<div class="content">
				<img src="{{ asset('templates/img/elifant.png') }}" alt="">
				<p>Клуб любителей творчества «ОчУмелые ручки» объединяет тех, кто хочет развиваться в разных видах рукоделия и творчества.
				Мы проводим <span>мастер-классы</span> по архитектурному моделированию, кулинарии, резьбе по дереву и другим направлениям.</p>
				<p>Выберите интересующий вас <span>вид творчества</span> в меню справа, ознакомьтесь с расписанием и записывайтесь на занятия после регистрации и входа на сайт.</p>
				@auth
					@if (auth()->user()->isVisitor() && $myBookings->isNotEmpty())
						<h2 class="my-bookings__title">Мои мастер-классы</h2>
						<ul class="my-bookings__list">
							@foreach ($myBookings as $booking)
								@php $mc = $booking->masterclass; @endphp
								<li class="my-bookings__item">
									<strong>{{ $mc->title }}</strong> — {{ $mc->category->name }},
									ведущий: {{ $mc->leader->fio }},
									{{ \App\Support\DateFormatter::formatRu($mc->mc_date) }},
									{{ \App\Support\TimeSlots::label($mc->start_time) }},
									{{ number_format((float) $mc->price, 2, ',', ' ') }} ₽
									(<a href="{{ route('category.show', $mc->category_id) }}">страница вида</a>)
								</li>
							@endforeach
						</ul>
					@endif
				@endauth
			</div>
			@include('partials.craft-menu', ['categories' => $categories])
		</div>
	</div>
</div>
@endsection
