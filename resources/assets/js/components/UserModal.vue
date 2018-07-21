<template>
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <ul class="collection">
                <a href="#" class="collection-item" v-for="user in users" @click="$emit('userSelected', user)">{{ user.name }}</a>
            </ul>
            <a class="waves-effect waves-light btn" @click="showUserForm = true"><i class="material-icons">add</i>Add User</a>
            <div class="user-form">

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'UserModal',
        data() {
            return {
                users: null,
                showUserForm: false
            }
        },
        created() {
            axios.get('/api/users/1').then(res => {
                this.users = res.data;
            });
        }
    }
</script>

<style scoped>
    .bottom {
        display: flex;
        justify-content: space-between;
    }
</style>