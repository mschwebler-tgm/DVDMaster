<template>
    <div>
        <div class="content-cards" ref="cardsContainer">
            <div v-for="content in data" v-if="data && !searchingActive" style="flex: 1">
                <div class="image" :style="'background-image: url(' + $root.getImagePath(content.poster_path, 'w154') + ')'">
                    <div class="darkener pointer pad" @click="$router.push(content.url)">
                        <span class="md-headline">{{ content.title }}</span>
                        <content-rating :content="content" :starSize="20" :initialCustomValue="content.custom_rating"></content-rating>
                    </div>
                </div>
            </div>
            <div v-if="placeholders" v-for="n in placeholders" style="flex: 1"></div>
        </div>
        <paginator identifier="content-cards" :toDispatch="paginateAction"></paginator>
    </div>
</template>

<script>
    export default {
        props: ['type', 'data', 'loading', 'searchingActive', 'paginateAction'],
        data() {
            return {
                viewMode: localStorage.getItem('viewMode') || 'grid',
                placeholders: false
            }
        },
        mounted() {
            this.updatePlaceholders();
            let update;
            window.onresize = () => {
                clearTimeout(update);
                update = setTimeout(this.updatePlaceholders, 100);
            }
        },
        methods: {
            updatePlaceholders() {
                let contentAmount = (this.type === 'movies' ?
                    this.$store.getters.MOVIES_GET_ALL.length :
                    this.$store.getters.SERIES_GET_ALL.length);
                let perRow = Math.floor(this.$refs.cardsContainer.offsetWidth / 154);
                this.placeholders = perRow === 0 || contentAmount % perRow === 0 ? 0 : perRow - (contentAmount % perRow);
            }
        },
        watch: {
            data() {
                let contentAmount = (this.type === 'movies' ?
                    this.$store.getters.MOVIES_GET_ALL.length :
                    this.$store.getters.SERIES_GET_ALL.length);
                if (contentAmount > 0) {
                    this.updatePlaceholders();
                }
            },
            '$parent.listView'(isListView) {
                if (!isListView) {
                    this.updatePlaceholders();
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import "~vue-material/src/components/MdAnimation/variables";
    @import "~vue-material/dist/theme/engine";

    .content-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .image {
        min-width: 154px;
        padding-bottom: 142%;
        /*height: 219px;*/
        position: relative;
        -webkit-background-size: 100% 100%;
        background-size: 100% 100%;
    }

    .darkener {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        -webkit-transition: opacity .2s $md-transition-stand-timing;
        -moz-transition: opacity .2s $md-transition-stand-timing;
        -ms-transition: opacity .2s $md-transition-stand-timing;
        -o-transition: opacity .2s $md-transition-stand-timing;
        transition: opacity .2s $md-transition-stand-timing;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .darkener span {
        color: white;
        text-align: center;
    }

    .image:hover .darkener {
        opacity: 1;
    }
</style>