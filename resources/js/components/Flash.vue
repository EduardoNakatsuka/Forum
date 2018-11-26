<template>
    <div class="alert alert-flash"
     :class="'alert-'+level"
     role="alert"
     v-show="show"
     v-text="body"
    >
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        created () {
            if (this.message) {
                this.flash(); //if we have a message, then flash it!
            }

            window.events.$on(
                'flash', data => this.flash(data) //we are listening to the event of if anywhere in our program we have a flash request, we fire the message associtated with it
            );
        },

        methods: {
            flash(data) { //flash(show)the message and set its body and visibility
                if(data) {
                    this.body = data.message;
                    this.level = data.level;
                }
                
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
