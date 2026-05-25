<ul class="menu">
	@foreach ($categories as $categoryRow)
		<li><a href="{{ route('category.show', $categoryRow) }}">{{ $categoryRow->name }}</a></li>
	@endforeach
</ul>
