{{-- Editing the question --}}

<div class="card mb-3" v-if="editing">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <input
                 type="text" 
                 class="form-control"
                 v-model="form.title"
                >
            </span>
            

        </div>
    </div>

    
    <div class="card-body">
        <div class="form-group">
            <textarea
             class="form-control"
             rows="10"
             v-model="form.body"
            >
            </textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button
             class="btn btn-primary btn-sm mr-1" 
             @click="update"
            >
                Update
            </button>

            <button
             class="btn btn-danger btn-sm" 
             @click="resetForm"
            >
                Cancel
            </button>

            @can ('update', $thread)
                <form 
                 action="{{ $thread->path() }}"
                 method="POST"
                 class="ml-a"
                >
                    @csrf
                    {{ method_field('DELETE') }}
                
                    <button
                     type="submit"
                     class="btn btn-link"
                    >
                        Delete Thread!
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
{{-- viewing the question --}}

<div class="card mb-3" v-else>
    <div class="card-header">
        <div class="level">
            <img
             src="{{ $thread->creator->avatar_path }}"
             alt="{{ $thread->creator->name }}"
             width="25"
             height="25"
             class="mr-1"
            >
            <span class="flex">
                <a href="/profiles/{{ $thread->creator->name }}">
                    {{ $thread->creator->name }}
                </a> posted:
                <span v-text="title"></span>
            </span>

        </div>
    </div>

    
    <div class="card-body" v-text="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-sm" @click="editing = true">Edit</button>       
    </div>

</div>
