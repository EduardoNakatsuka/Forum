<div id="reply-{{ $reply->id }}" class="card mb-3">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="/profiles/{{ $reply->owner->name }}">
                    {{ $reply->owner->name }}
                </a> 
                <small>
                    said 
                    {{ $reply->created_at->diffForHumans() }}
                    ...
                </small>
            </h5>

            <div>
                <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                    @csrf
                    <button class="btn btn-outline-primary btn-sm" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count }}  {{ str_plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
                
            </div>
        </div>
    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>