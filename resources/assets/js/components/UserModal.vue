<template>
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <h4>Borrow to</h4>
            <ul class="collection" style="margin-bottom: 30px; margin-top: 25px">
                <a href="#" class="collection-item" v-for="user in users" @click="$emit('userSelected', user)">{{ user.name }}</a>
            </ul>
            <hr>
            <div class="row" style="margin-top: 25px">
                <div class="col s6"></div>
                <div class="input-field col s4" style="margin: 0">
                    <input id="user_name" type="text" class="validate" v-model="newUserName" @keyup.enter="addUser">
                    <label for="user_name">Name</label>
                </div>
                <div class="col s2" style="margin-top: 10px">
                    <a class="waves-effect waves-light btn" @click="addUser"><i class="material-icons">add</i>Add User</a>
                </div>
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
                newUserName: null
            }
        },
        created() {
            axios.get('/api/users/1').then(res => {
                this.users = res.data;
            });
        },
        methods: {
            addUser() {
                axios.post('/api/addUser', { name: this.newUserName }).then(res => {
                    this.users.push(res.data);
                    this.newUserName = null;
                }).catch(err => {
                    M.toast({html: 'User already exists.', classes: 'complete-toast'});
                    this.newUserName = null;
                })
            }
        }
    }
</script>

<style scoped>
    .bottom {
        display: flex;
        justify-content: flex-end;
    }
</style>