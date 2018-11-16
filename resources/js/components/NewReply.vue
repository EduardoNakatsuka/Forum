<template>
    <div v-if="signedIn">
        <!-- @if (auth()->check()) -->
            <!-- <form method="POST" action="{{ $thread->path() . '/replies' }}"> -->
                
                <div class="form-group mt-3">
                    <textarea
                     class="form-control"
                     name="body"
                     id="body"
                     rows="5"
                     placeholder="Have something to say?"
                     required
                     v-model="body"
                    >
                    </textarea>
                </div>
                
                <button
                 type="submit"
                 class="btn btn-default"
                 @click="addReply"
                >
                    Post
                </button>

            <!-- </form> -->
        <!-- @else -->
            <!-- <p class="text-center">
                Please 
                <a href="{{ route('login') }}">
                sign in
                </a>
                to participate in this discussion.
            </p> -->
        <!-- @endif -->
    </div>
</template>

<script>
    export default {
        props: ['endpoint'],
        
        data() {
            return {
                body: '',
            };
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                .then(({data}) => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
                })
            }
        }
        
    }
</script>
