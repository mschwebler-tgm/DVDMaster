<template>
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <ul class="collection">
                <a href="#" class="collection-item" v-for="user in users" @click="$emit('userSelected', user)">{{ user.name }}</a>
            </ul>
            <div class="flex-box flex-justify-center">
                <div style="width: 200px" >
                    <input placeholder="Name" :class="{invalid}" id="new-user" type="text" @keyup.enter="selectNewUser" ref="input" v-model="username">
                    <span id="helper-text" v-show="invalid">User already exists</span>
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
                invalid: false,
                username: ''
            }
        },
        created() {
            this.$store.dispatch('USERS_ACTION_GET_All_EXCEPT_ME');
        },
        methods: {
            selectNewUser() {
                if (this.invalid || this.username === '') { return }
                this.$store.dispatch('USERS_ACTION_CREATE_USER', this.$refs.input.value).then(user => {
                    this.$emit('userSelected', user);
                });
            },
            usernameIsInvalid() {
                let name = this.$refs.input.value;
                for (let user of this.$store.getters.USERS_GET_ALL) {
                    if (user.name === name) {
                        return true;
                    }
                }
                return false;
            }
        },
        watch: {
            username() {
                this.invalid = this.usernameIsInvalid();
            }
        },
        computed: {
            users() {
                return this.$store.getters.USERS_GET_ALL;
            }
        }
    }
</script>

<style scoped>
    #helper-text {
        color: #F44336;
    }

    .invalid {
        border-bottom: 1px solid #F44336;
        -webkit-box-shadow: 0 1px 0 0 #F44336;
        -moz-box-shadow: 0 1px 0 0 #F44336;
        box-shadow: 0 1px 0 0 #F44336;
    }
</style>