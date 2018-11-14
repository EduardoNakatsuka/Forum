<template>
    <button type="submit" :class="classes" @click="toggle">
        <span text>&#9829;</span>
        <span v-text="count"></span>
    </button>    
</template>

<script>
    export default {
        props: ['reply'], //we are using a reply prop so we need to pass it

        data() {
            return {
                count: this.reply.favoritesCount, //we show the path/function of the count
                active: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default']; //if favorited, show primary, otherwise show default
            },

            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                return this.active ? this.destroy() : this.create(); // ? = if so... : = otherwise
            },

            create() {
                axios.post(this.endpoint);

                this.active = true; //when favored it will shine
                this.count++; //it will incrememt the number when clicked
            },

            destroy() {  
                axios.delete(this.endpoint); //the path for the favorite.

                this.active = false;
                this.count--;
            }
        }
    }
</script>

