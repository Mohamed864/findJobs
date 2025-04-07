<x-layout>
    <x-slot name="title">List of Jobs</x-slot>
    <h1>Available Jobs</h1>
    <ul>
        @forelse ($jobs as $job)
            <li><a href="{{ route('jobs.show', $job->id) }}"> {{ $job->title }} - {{ $job->description }}</li>
        @empty
            <li>No Jobs Available</li>
        @endforelse
    </ul>
</x-layout>
