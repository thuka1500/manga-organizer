@extends('app')

@section('title', __('manga.index.title'))

@section('content')
    <h1>{{ __('manga.index.title') }}</h1>

    <p>
        @auth
            {!! Html::decode(Html::linkRoute('mangas.manage', '<i class="fas fa-wrench"></i> ' . __('manga.index.manage'), [], ['class' => 'btn btn-dark btn-sm'])) !!}
        @endauth

        @guest
            {!! Html::decode(Html::linkRoute('recommendations.create', '<i class="far fa-envelope-open"></i> ' . __('manga.index.recommend_me_a_manga'), [], ['class' => 'btn btn-primary btn-sm'])) !!}
        @endguest
    </p>

    @if ($mangas->count() == 0)
        <p class="lead"><i>{{ __('manga.no_mangas_yet') }}</i></p>
    @else
        <div class="card-columns">
            @foreach ($mangas as $manga)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $manga->name }}

                            @if ($manga->malItem && $manga->malItem->url)
                                {!! Html::decode(Html::link($manga->malItem->url, '<i class="fas fa-external-link-alt"></i>', ['title' => __('manga.index.manga_at_myanimelist', ['name' => $manga->name])])) !!}
                            @endif
                        </h5>

                        @if ($manga->malItem && $manga->malItem->url)
                            <div>{{ __('manga.index.mal_score') }}: {{ formatNumber($manga->malItem->score, 2) }}</div>
                        @endif

                        <div class="pb-3">
                            {{ __('validation.attributes.is_completed') }}: {{ formatBool($manga->is_completed) }}
                        </div>

                        @if ($manga->malItem && $manga->malItem->image_url)
                            <div class="text-center">
                                {{ Html::image($manga->malItem->image_url, $manga->name, ['class' => 'img-fluid']) }}
                            </div>
                        @endif

                        <div>
                            <table class="table table-striped table-sm mt-4">
                                <tbody>
                                @foreach ($manga->volumes as $volume)
                                    <tr>
                                        <td>{{ $volume->no }}</td>
                                    </tr>
                                @endforeach

                                @foreach ($manga->specials as $special)
                                    <tr>
                                        <td>{{ $special->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection