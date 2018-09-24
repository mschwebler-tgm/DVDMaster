<template>
    <div class="order-filters">
        <div v-for="orderField in orderFields">
            <md-button @click="updateFilter(orderField)">{{ orderField.label }}</md-button>
            <md-icon class="direction" :class="{active: activeOrderField === orderField.field}">{{ activeOrderDirection === orderField.direction ? 'arrow_downward' : 'arrow_upward' }}</md-icon>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['type'],
        data() {
            return {
                orderFields: [
                    {label: 'Title', field: 'title', direction: 'asc'},
                    {label: 'Duration', field: 'duration', direction: 'asc'},
                    {label: 'Popularity', field: 'popularity', direction: 'desc'},
                    {label: 'Rating', field: 'custom_rating', direction: 'desc'}
                ]
            }
        },
        methods: {
            updateFilter(orderField) {
                let existingOrder = this.$store.getters[this.type + '_GET_FILTER'].order;
                if (existingOrder && existingOrder.field === orderField.field) {
                    this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {
                        type: 'order',
                        data: {
                            field: orderField.field,
                            direction: (existingOrder.direction === 'desc' ? 'asc' : 'desc')
                        }
                    });
                } else {
                    this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type: 'order', data: orderField});
                }
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
            }
        },
        computed: {
            activeOrderDirection() {
                const orderFilter = this.$store.getters[this.type + '_GET_FILTER'].order;
                if (!orderFilter) {
                    return 'asc';
                }
                return orderFilter.direction;
            },
            activeOrderField() {
                const orderFilter = this.$store.getters[this.type + '_GET_FILTER'].order;
                return orderFilter ? orderFilter.field : null;
            }
        }
    }
</script>

<style scoped>

    .order-filters {
        display: flex;
        justify-content: space-around;
        overflow-x: scroll;
        padding-top: 5px;
        margin-bottom: -20px;
    }

    .order-filters > div {
        display: flex;
        align-items: center;
    }

    .direction:not(.active) {
        opacity: 0;
    }

    .direction.active {
        opacity: 1;
    }

    @media only screen and (max-width: 600px) {
        .order-filters {
            padding-left: 50%;
            padding-top: 0;
        }
    }

</style>