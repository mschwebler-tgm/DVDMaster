import VueRouter from 'vue-router'

// lazy load components
const AddMovie = (resolve) => require(['./components/AddMoviePage/AddMovie'], resolve);
const MoviePage = (resolve) => require(['./components/MoviePage/MoviePage'], resolve);
const MovieEdit = (resolve) => require(['./components/MoviePage/Edit'], resolve);
const MovieView = (resolve) => require(['./components/MoviePage/View'], resolve);
const DVDPage = (resolve) => require(['./components/DVDPage/Index'], resolve);
const SeriesPage = (resolve) => require(['./components/SeriesPage/Index'], resolve);

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path: '/movies',
            component: DVDPage
        },
        {
            path: '/series',
            component: SeriesPage
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
            path: '/',
            redirect: '/movies'
        }
    ]
});