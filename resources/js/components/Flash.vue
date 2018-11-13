<template>
    <div class="alert alert-success alert-flash" role="alert" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: '',
                show: false
            }
        },

        created () {
            if (this.message) {
                this.flash(this.message); //if we have a message, then flash it!
            }

            window.events.$on('flash', message => { //we are listening to the event of if anywhere in our program we have a flash request, we fire the message associtated with it
                this.flash(message);
            });
        },

        methods: {
            flash(message) { //flash(show)the message and set its body and visibility
                this.body = message;
                this.show = true;

                this.hide(); //after we flash it we need to hide it lol
            },

            hide() { //how we are going to hide the message after 3 secs
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
