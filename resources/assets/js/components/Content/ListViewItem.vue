<template>
    <div class="flex flex-align-center toContent" v-if="content" :style="'background: ' + (rentedBy ? notAvailableBackground : '')">
        <div class="flex flex-align-center flex-justify-space-between toContent" style="flex: 1;">
            <div class="title toContent">
                <img class="cover toContent" :src="$root.getImagePath(content.poster_path, 'w92')" width="92" height="138">
                <div class="flex flex-column flex-justify-center" style="height: 130px; position: relative">
                    <span class="md-title" style="margin-bottom: 8px">{{ content.title }}</span>
                    <div>
                        <template v-for="genre in getGenreNames()">
                            <md-chip class="genre-chip">{{ genre }}</md-chip>
                        </template>
                    </div>
                    <div class="duration md-caption desktop-only">{{ content.duration }} min. {{ (type === 'SERIES' ? '/ Episode' : '') }}</div>
                </div>
            </div>
            <div class="hints toContent">
                <span class="md-title toContent" style="opacity: 0;">A</span>
                <div class="flex toContent">
                    <div v-if="rentedBy">
                        <md-icon>import_export</md-icon>
                        <md-tooltip md-direction="bottom">
                            Borrowed by {{ rentedBy.name }} {{ rentalTimeAgo }}
                        </md-tooltip>
                    </div>
                    <div v-if="isBlueRay">
                        <md-icon>album</md-icon>
                        <md-tooltip md-direction="bottom">Blue Ray</md-tooltip>
                    </div>
                    <div v-if="isTrueStory">
                        <md-icon>event</md-icon>
                        <md-tooltip md-direction="bottom">True story</md-tooltip>
                    </div>
                    <div v-if="isBasedOnBook">
                        <md-icon>book</md-icon>
                        <md-tooltip md-direction="bottom">Based on book</md-tooltip>
                    </div>
                </div>
            </div>
            <div class="actors-container flex flex-align-center toContent">
                <div class="actors">
                    <template v-for="actor in getActorNames()">
                        <span class="md-caption pointer">{{ actor }}</span>
                        <br>
                    </template>
                </div>
                <div>
                    <md-icon :style="'opacity: ' + (content.actors.length !== 0 ? 1 : 0)">person</md-icon>
                    <md-tooltip md-direction="right">Actors</md-tooltip>
                </div>
            </div>
        </div>
        <content-rating :content="content" :initialCustomValue="content.custom_rating" class="desktop-only"></content-rating>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['content', 'type'],
        data() {
            return {
                notAvailableBackground: 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAM0lEQVQoU2NkIBIwEqmOgSoKpRgYGJ7BbMRlIooikGJsCjEUYVOIVREuE7EGBFV8jWIyACo2BAs4XVWOAAAAAElFTkSuQmCC)'
            }
        },
        methods: {
            getGenreNames() {
                let names = [];
                for (let i = 0; this.content.genres && i < this.content.genres.length && names.length < 3; i++) {
                    names.push(this.content.genres[i].name);
                }
                return names;
            },
            getActorNames() {
                let names = [];
                for (let i = 0; this.content.actors && i < this.content.actors.length && names.length < 4; i++) {
                    names.push(this.content.actors[i].name);
                }
                return names;
            },
        },
        computed: {
            rentedBy() {
                if (this.content.pending_rental.length > 0) {
                    return this.content.pending_rental[0].user;
                }
                return null;
            },
            isBlueRay() {
                return !!this.content.blue_ray;
            },
            isTrueStory() {
                return !!this.content.true_story;
            },
            isBasedOnBook() {
                return !!this.content.based_on_book;
            },
            rentalTimeAgo() {
                return moment(this.content.pending_rental[0].created_at).fromNow();
            }
        }
    }
</script>

<style scoped>
    span {
        cursor: text;
    }
    
    .title {
        width: 400px;
    }

    .cover {
        margin-right: 20px;
        height: 130px;
        width: 92px;
        float: left;
    }

    .genre-chip {
        margin-bottom: 4px;
    }

    .duration{
        position: absolute;
        bottom: 0;
        left: 0;
    }

    .actors-container {
        padding-right: 11%;
    }

    .actors {
        padding-right: 10px;
        margin-right: 10px;
        border-right: 1px solid var(--md-theme-default-divider, rgba(0,0,0,0.12));
        width: 170px
    }

    .actors span {
        float: right;
        white-space: nowrap;
        -webkit-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -moz-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -ms-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .actors span:hover {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.87));
        /*color: rgba(0, 0, 0, 0.64);*/
    }

    .hints {
        padding-left: 15px;
        padding-right: 15px;
        flex: 1;
    }

    .hints i {
        /*color: var(--md-theme-default-divider, rgba(0,0,0,0.12)) !important;*/
        padding-left: 15px;
        padding-right: 15px;
        -webkit-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -moz-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -ms-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
    }

    .hints i:hover {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.87)) !important;
    }

    @media only screen and (max-width: 960px) {
        .actors-container {
            padding-right: 6%;
        }
    }

    @media only screen and (max-width: 850px) {
        .actors-container {
            display: none;
        }
    }

    @media only screen and (max-width: 1640px) {
        .hints {
            display: none;
        }
        .title {
            width: auto;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1430px) {
        .actors-container {
            display: none;
        }
        .hints {
            display: block;
            margin-right: 13%;
            flex: initial;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1230px) {
        .hints {
            display: block;
            margin-right: 6%;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1100px) {
        .hints {
            display: none;
        }
    }
</style>