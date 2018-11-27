<template>
    <div :id="'reply-'+id" class="card mb-3">
        <div class="card-header" :class="isBest ? 'bg-success': 'card-default'">
            <div class="level">
                <h5 class="flex">
                    <a 
                     :href="'/profiles/'+data.owner.name"
                     v-text="data.owner.name">
                    </a> 
                    said 
                    <span v-text="ago"></span>
                    ...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
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
                    >
                    Update
                    </button>

                    <button
                    class="btn btn-sm btn-link"
                    @click="editing = false"
                    type="button"
                    >
                    Cancel
                    </button>
                </form>
            </div>
            
            <div v-else v-html="body"></div>
        </div>
        
        <div class="card-footer level">
            <div v-if="authorize('updateReply', reply)">
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
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: false,
                reply: this.data
            };
        },

        computed: {
            ago() {
                return moment(this.data.created_at + 'Z').fromNow();
            },

            // signedIn() {
            //     return window.App.signedIn;
            // },

            // canUpdate() {
            //     // return this.data.user_id == App.user.id;
            //     return this.data.user_id == _.get(window, 'App.user.id', -1);
            // }
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.data.id, {
                        body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });                ;
                
                this.editing = false; //this will close the vue

                flash('Updated!'); //will flash a updated msg
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);

                // $(this.$el).fadeOut(300, () => { //this will make the reply fadeout in .30secs
                //     flash('Your reply has been deleted.');
                // });
            },

            markBestReply() {
                this.isBest = true;
            }
        }
    }
</script>
