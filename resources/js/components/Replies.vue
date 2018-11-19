<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <new-reply
         @created="add"
        >
        </new-reply>
        
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/Collection.js';

    export default {        
        components: { Reply, NewReply },

        mixins: [collection],
        
        data() {
            return {
                dataSet: false
            }
        },

        created() { //we don't accept data anymore so when sth is created, we will fetch it
            this.fetch();
        },

        methods: {
            fetch(page) { //later we will make an axios request to get and refresh the new url
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) { //after we have a response we will add sth to the ITEMS data and save it
                this.dataSet = data; 
                this.items = data.data;

                window.scrollTo(0, 0);
            }
        }
    }
</script>
