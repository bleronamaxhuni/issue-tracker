@props(['project'])

@if ($badge = $projectPresenter->deadlineBadge($project))
    <span class="text-xs font-medium {{ $badge['class'] }}">{{ $badge['label'] }}</span>
@endif
