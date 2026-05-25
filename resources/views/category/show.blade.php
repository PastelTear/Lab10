@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="main">
	<div class="row">
		<div class="hover"></div>
		<div class="title">{{ $category->name }}</div>
		<div class="row--small grid between">
			<div class="content">
				<img src="{{ asset('templates/' . ($category->image ?: 'img/elifant.png')) }}" alt="">
				@if ($category->description)
					@foreach (preg_split("/\r\n|\n|\r/", $category->description) as $paragraph)
						@if (trim($paragraph) !== '')
							<p>{!! nl2br(e($paragraph)) !!}</p>
						@endif
					@endforeach
				@endif
			</div>
			@include('partials.craft-menu', ['categories' => $categories])
		</div>

		<div class="row shedule">
			<div class="row--small">
				<h2>Расписание</h2>
				<div class="drivers">
					@if ($masterclasses->isEmpty())
						<p>Пока нет запланированных мастер-классов.</p>
					@else
						@foreach ($masterclasses as $masterclass)
							@php
								$freePlaces = $masterclass->freePlaces();
								$canBook = auth()->check()
									&& auth()->user()->isVisitor()
									&& $freePlaces > 0
									&& ! in_array($masterclass->id, $bookedIds, true);
							@endphp
							<div class="driver grid">
								<div class="driver-left grid">
									<div class="driver-photo">
										<img src="{{ asset('templates/img/driver1.png') }}" alt="">
									</div>
									<div class="driver-text">
										<div class="driver-name">{{ $masterclass->leader->fio }} — {{ $masterclass->title }}</div>
										<div class="driver-desc">{!! nl2br(e($masterclass->description)) !!}</div>
										<div class="driver-meta">
											Стоимость: {{ number_format((float) $masterclass->price, 2, ',', ' ') }} ₽.
											Свободно мест: {{ $freePlaces }} из {{ $masterclass->max_participants }}.
										</div>
									</div>
								</div>
								<div class="driver-right">
									@if ($canBook)
										<a class="driver-btn btn" href="{{ route('book.confirm', $masterclass) }}">записаться</a>
									@endif
									<div class="driver-time">
										{{ \App\Support\DateFormatter::formatRu($masterclass->mc_date) }}
										{{ \App\Support\TimeSlots::label($masterclass->start_time) }}
									</div>
								</div>
							</div>
						@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
