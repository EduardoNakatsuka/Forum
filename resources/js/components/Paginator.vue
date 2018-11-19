<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li class="page-item" 
         v-show="prevUrl" 
         rel="prev" 
         @click.prevent="page--"
        >
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>

        <li class="page-item" 
         v-show="nextUrl" 
         rel="next" 
         @click.prevent="page++"
        >
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['dataSet'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: { //we will keep an eye if these dataSet settings change and update them in the data
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },

            page() {
                this.broadcast().updateUrl(); //if the page changes, clearly the user wants new data
            }
        },

        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl; // we should only display it if we have a previous or a next
            }
        },

        methods: {
            broadcast() { //we broadcast changed and the new page value
                return this.$emit('changed', this.page);
            },

            updateUrl() { //we are going to update the page url in browser
                history.pushState(null, null, '?page=' + this.page);
            }
        }

    }
</script>

