<template>
    <div :id="'reply-'+id" class="card mb-3">
        <div class="card-header" :class="isBest ? 'bg-success': 'card-default'">
            <div class="level">
                <h5 class="flex">
                    <a 
                     :href="'/profiles/'+reply.owner.name"
                     v-text="reply.owner.name">
                    </a> 
                    said 
                    <span v-text="ago"></span>
                    ...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form>
                    <div class="form-group">
                        <textarea
                         class="form-control"
                         v-model="body"
                         required
                        >
                        </textarea>
                    </div>

                    <button
                     class="btn btn-sm btn-primary"
                     @click="update"
                     type="button"
                    >
                        Update
                    </button>

                    <button
                     class="btn btn-sm btn-link"
                     @click="cancel"
                     type="button"
                    >
                        Cancel
                    </button>
                </form>
            </div>
            
            <div v-else v-html="body"></div>
        </div>
        
        <div
         class="card-footer level"
         v-if="authorize('owns', reply) || authorize('owns', reply.thread)"
        >
            <div v-if="authorize('owns', reply)">
                <button
                 class="btn btn-sm mr-2"
                 @click="editing = true"
                >
                    Edit
                </button>

                <button
                 class="btn btn-sm btn-danger mr-2"
                 @click="destroy"
                >
                    Delete
                </button>
            </div>

            <button
             class="btn btn-sm btn-info ml-a"
             @click="markBestReply"
             v-if="authorize('owns', reply.thread)"
             v-show="! isBest"
            >
                Best Reply
            </button>
        </div>
    </div>
</template>


<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at + 'Z').fromNow();
            },

            // signedIn() {
            //     return window.App.signedIn;
            // },

            // canUpdate() {
            //     // return this.reply.user_id == App.user.id;
            //     return this.reply.user_id == _.get(window, 'App.user.id', -1);
            // }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.id, {
                        body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
                
                this.editing = false; //this will close the vue

                flash('Updated!'); //will flash a updated msg
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);

                // $(this.$el).fadeOut(300, () => { //this will make the reply fadeout in .30secs
                //     flash('Your reply has been deleted.');
                // });
            },

            cancel() {
                this.body = this.reply.body;
                this.editing = false;
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best')

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>
