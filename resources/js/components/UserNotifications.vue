<template>
    <li class="dropdown" v-if="notifications.length">
        <a
         class="nav-link dropdown-toggle"
         data-toggle="dropdown"
         href="#"
        >
            &#128276;
        </a>
        
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a
                 :href="notification.data.link"
                 v-text="notification.data.message"
                 @click="markAsRead(notification)"
                >
                </a>
            </li>
        </ul>

    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
            }
        }
    }
</script>

