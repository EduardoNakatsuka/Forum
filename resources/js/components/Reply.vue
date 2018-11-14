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
            }
        }
    }
</script>
