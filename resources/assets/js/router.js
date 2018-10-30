import VueRouter from 'vue-router'

// lazy load components
import AddMovie from './components/AddMoviePage/AddMovie';
const MoviePage = (resolve) => require(['./components/MoviePage/MoviePage'], resolve);
const MovieEdit = (resolve) => require(['./components/MoviePage/Edit'], resolve);
const MovieView = (resolve) => require(['./components/MoviePage/View'], resolve);
const SeriesPage = (resolve) => require(['./components/SeriesPage/SeriesPage'], resolve);
const SeriesView = (resolve) => require(['./components/SeriesPage/View'], resolve);
const SeriesEdit = (resolve) => require(['./components/SeriesPage/Edit'], resolve);
const ContentList = (resolve) => require(['./components/Content/Index'], resolve);

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path: '/movies',
            name: 'MOVIES',
            component: ContentList
        },
        {
            path: '/series',
            name: 'SERIES',
            component: ContentList
        },
        {
            path: '/addMovie',
            component: AddMovie
        },
        {
            path: '/movie/:id',
            component: MoviePage,
            props: true,
            children: [
                {
                    path: 'edit',
                    component: MovieEdit
                },
                {
                    path: '/',
                    component: MovieView
                }
            ]
        },
        {
            path: '/series/:id',
            component: SeriesPage,
            props: true,
            children: [
                {
                    path: 'edit',
                    component: SeriesEdit
                },
                {
                    path: '/',
                    component: SeriesView
                }
            ]
        },
        {
            path: '/',
            redirect: '/movies'
        }
    ]
});