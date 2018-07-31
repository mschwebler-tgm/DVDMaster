<template>
    <div class="custom-wrapper">
        <input id="custom_genres" type="text" class="validate">
    </div>
</template>

<script>
    export default {
        props: ['initial', 'classes'],
        mounted() {
            this.initTagsinput();
            this.initValues();
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

                this.initValues(input);

                input.on('itemAdded', () => this.$emit('change', input.tagsinput('items')));
                input.on('itemRemoved', () => this.$emit('change', input.tagsinput('items')));
            },
            initValues(input) {
                if (!this.initial) return;
                for (let genre of this.initial) {
                    input.tagsinput('add', genre);
                }
            }
        }
    }
</script>

<style scoped>

</style>