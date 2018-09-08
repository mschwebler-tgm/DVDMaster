<template>
    <div class="custom-wrapper">
        <input id="custom_genres" type="text" class="validate">
    </div>
</template>

<script>
    export default {
        props: ['initial', 'classes'],
        data() {
            return {
                preventEmit: false
            }
        },
        mounted() {
            this.initTagsinput();
        },
        methods: {
            initTagsinput() {
                let genres = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '/api/genres'
                });
                genres.initialize();

                let input = $('#custom_genres');
                input.tagsinput({
                    itemValue: 'id',
                    itemText: 'name',
                    tagClass: this.classes || 'custom-tag',
                    typeaheadjs: {
                        name: 'genres',
                        displayKey: 'name',
                        source: genres.ttAdapter()
                    }
                });

                this.initValues(input, this.initial);

                input.on('itemAdded', () => !this.preventEmit && this.$emit('change', input.tagsinput('items')));
                input.on('itemRemoved', () => !this.preventEmit && this.$emit('change', input.tagsinput('items')));
            },
            initValues(input, values) {
                if (!values || !input) return;
                this.preventEmit = true;
                for (let genre of values) {
                    input.tagsinput('add', genre);
                }
                this.preventEmit = false;
            },
            update(genres) {
                let input = $('#custom_genres');
                input.tagsinput('removeAll');
                this.initValues(input, genres);
            }
        }
    }
</script>

<style scoped>

</style>