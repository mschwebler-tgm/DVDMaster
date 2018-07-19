<template>
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <ul class="collection">
                <a href="#" class="collection-item" v-for="user in users" @click="$emit('userSelected', user)">{{ user.name }}</a>
            </ul>
        </div>
        <div class="modal-footer">
            <div class="bottom">
                <a class="waves-effect waves-light btn"><i class="material-icons">add</i>Add User</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'UserModal',
        data() {
            return {
                users: null
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