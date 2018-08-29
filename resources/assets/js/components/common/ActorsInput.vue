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

                this.initValues(input, this.initial);

                input.on('itemAdded', () => !this.preventEmit && this.$emit('change', input.tagsinput('items')));
                input.on('itemRemoved', () => !this.preventEmit && this.$emit('change', input.tagsinput('items')));
            },
            initValues(input, values) {
                if (!values || !input) return;
                this.preventEmit = true;
                for (let actor of values) {
                    input.tagsinput('add', actor);
                }
                this.preventEmit = false;
            },
            update(actors) {
                let input = $('#custom_actors');
                input.tagsinput('removeAll');
                this.initValues(input, actors);
            }
        }
    }
</script>

<style scoped>

</style>