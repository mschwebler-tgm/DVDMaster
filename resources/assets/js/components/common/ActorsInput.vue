<template>
    <div class="custom-wrapper">
        <input id="custom_actors" type="text" class="validate">
    </div>
</template>

<script>
    export default {
        props: ['initial', 'classes'],
        mounted() {
            this.initTagsinput();
        },
        methods: {
            initTagsinput() {
                let actors = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '/api/actors'
                });
                actors.initialize();

                let input = $('#custom_actors');
                input.tagsinput({
                    itemValue: 'id',
                    itemText: 'name',
                    tagClass: this.classes || 'custom-tag',
                    typeaheadjs: {
                        name: 'actors',
                        displayKey: 'name',
                        source: actors.ttAdapter()
                    }
                });

                this.initValues(input);

                input.on('itemAdded', () => this.$emit('change', input.tagsinput('items')));
                input.on('itemRemoved', () => this.$emit('change', input.tagsinput('items')));
            },
            initValues(input) {
                if (!this.initial) return;
                for (let actor of this.initial) {
                    input.tagsinput('add', actor);
                }
            }
        }
    }
</script>

<style scoped>

</style>