<script>
    export default {
        props: ['attributes'],

        data() {
            return {
                editing: false,
                body: this.attributes.body
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });
                
                this.editing = false; //this will close the vue

                flash('Updated!'); //will flash a updated msg
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);

                $(this.$el).fadeOut(300, () => { //this will make the reply fadeout in .30secs
                    flash('Your reply has been deleted.');
                });

            }
        }
    }
</script>
