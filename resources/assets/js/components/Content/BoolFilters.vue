<template>
    <table>
        <tr>
            <td>
                <span class="icon-filters"
                      :class="{'icon-filter-active': (filters && filters.indexOf('borrowed') !== -1)}"
                      @click="toggleFilter('borrowed')">
                    <md-icon>import_export</md-icon>
                    <md-tooltip md-direction="left">Borrowed</md-tooltip>
                </span>
            </td>
            <td>
                <span class="icon-filters"
                      :class="{'icon-filter-active': (filters && filters.indexOf('blue_ray') !== -1)}"
                      @click="toggleFilter('blue_ray')">
                    <md-icon>album</md-icon>
                    <md-tooltip md-direction="right">Blue Ray</md-tooltip>
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="icon-filters"
                      :class="{'icon-filter-active': (filters && filters.indexOf('true_story') !== -1)}"
                      @click="toggleFilter('true_story')">
                    <md-icon>event</md-icon>
                    <md-tooltip md-direction="left">True story</md-tooltip>
                </span>
            </td>
            <td>
                <span class="icon-filters"
                      :class="{'icon-filter-active': (filters && filters.indexOf('based_on_book') !== -1)}"
                      @click="toggleFilter('based_on_book')">
                    <md-icon>book</md-icon>
                    <md-tooltip md-direction="right">Based on book</md-tooltip>
                </span>
            </td>
        </tr>
    </table>
</template>

<script>
    export default {
        props: ['type', 'value'],
        methods: {
            toggleFilter(boolFilter) {
                if (this.value.indexOf(boolFilter) !== -1) {
                    this.value.splice(this.value.indexOf(boolFilter), 1);
                } else {
                    this.value.push(boolFilter);
                }
                this._updateBoolFilters();
            },
            _updateBoolFilters() {
                this.$emit('input', this.value);
            },
        },
        computed: {
            filters() {
                return this.$store.getters[this.type + '_GET_FILTER'].bool;
            }
        }
    }
</script>

<style scoped>

    .icon-filters {
        padding: 5px;
        cursor: pointer;
    }

    .icon-filters:hover i {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.54)) !important;
        -webkit-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -moz-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -ms-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-filters.icon-filter-active i {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.54)) !important;
    }

</style>