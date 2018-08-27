<template>
    <div id="user-modal">
        <md-dialog :md-active.sync="showModal">
            <md-dialog-title>Borrow to</md-dialog-title>
            <md-dialog-content>
                <md-list>
                    <md-list-item v-for="user in users" @click="$emit('userSelected', user)" :key="user.id">{{ user.name }}</md-list-item>
                </md-list>
                <md-divider></md-divider>
                <md-field :class="{'md-invalid': invalid}">
                    <label>New user</label>
                    <md-input v-model="username" @keyup.enter="selectNewUser"></md-input>
                    <span class="md-error">User already exists</span>
                </md-field>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-accent" @click="showModal = false">Cancel</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
    export default {
        name: 'UserModal',
        props: ['show'],
        data() {
            return {
                invalid: false,
                username: '',
                showModal: false,
            }
        },
        created() {
            this.$store.dispatch('USERS_ACTION_GET_All_EXCEPT_ME');
            this.showModal = this.show;
        },
        methods: {
            selectNewUser() {
                if (this.invalid || this.username === '') { return }
                this.$store.dispatch('USERS_ACTION_CREATE_USER', this.username).then(user => {
                    this.$emit('userSelected', user);
                });
            },
            usernameIsInvalid() {
                let name = this.username;
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
            },
            showModal(show) {
                this.$emit('update:show', show);
            },
            show(show) {
                this.showModal = show;
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