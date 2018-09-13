<template>
    <div>
        <md-chip v-for="item in selectedItems" :key="item.id">{{ item.name }}</md-chip>
        <md-autocomplete v-model="search" @md-changed="getItems" @md-opened="getItems" @md-selected="selectItem" @md-closed="onClose"
                         :md-options="items" v-if="items">
            <label>{{ (type.charAt(0).toUpperCase() + type.slice(1)) }}</label>

            <template slot="md-autocomplete-item" slot-scope="{ item, term }">
                <div v-if="imageKey" style="float: left; width: 50px">
                    <img :src="$root.getImagePath(item[imageKey], 'w45')" class="preview-image">
                </div>
                <md-highlight-text :md-term="term">{{ item.name }}</md-highlight-text>
            </template>

            <template slot="md-autocomplete-empty" slot-scope="{ term }">
                No {{ (type.charAt(0).toUpperCase() + type.slice(1)) }} matching "{{ searchTerm }}" were found. <a @click="noop()">Create a new</a> one!
            </template>
        </md-autocomplete>
    </div>
</template>

<script>
    export default {
        props: ['type', 'imageKey'],
        data() {
            return {
                search: null,
                items: [],
                selectedItems: [],
                resetSearchOnClose: false,
            }
        },
        methods: {
            getItems() {
                this.items = new Promise((resolve, reject) => {
                    let payload = null;
                    if (this.search) {
                        payload = {q: this.search}
                    }
                    axios.get('/api/' + this.type, {params: payload}).then(res => {
                        // set toLowerCase property as the autocomplete makes use of that function
                        let mappedItems = res.data
                            .filter(actor => !this.isDuplicate(actor))
                            .map(actor => ({
                                'id': actor.id,
                                'name': actor.name,
                                'profile_path': actor.profile_path,
                                'toLowerCase': () => actor.name.toLowerCase(),
                                'toString': () => actor.name
                            }));
                        resolve(mappedItems);
                    }).catch(reject);
                });
            },
            selectItem(item) {
                this.resetSearchOnClose = true;
                !this.isDuplicate(item) && this.selectedItems.push(item);
            },
            onClose() {
                if (this.resetSearchOnClose) {
                    this.search = '';
                }
                this.resetSearchOnClose = false;
            },
            isDuplicate(item) {
                for (let existing of this.selectedItems) {
                    if (item.id === existing.id) {
                        return true;
                    }
                }
            }
        },
        computed: {
            searchTerm() {
                return _.isObject(this.search) ? this.search.name : this.search;
            }
        }
    }
</script>

<style scoped>
    .preview-image {
        margin-right: 10px;
        max-width: 45px;
        max-height: 56px;
    }
</style>