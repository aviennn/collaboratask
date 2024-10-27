<!-- activities.blade.php -->
<ul>
    @foreach($activities as $activity)
        <li>
            <p>{{ $activity->description }}</p>
            <small>{{ $activity->created_at->diffForHumans() }}</small>
        </li>
    @endforeach
</ul>