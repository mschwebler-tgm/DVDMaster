import VueRouter from 'vue-router'

//Define route components
const Foo = {template: '<div>Foo</div>'};

// lazy load components
const MovieCards = (resolve) => require(['./components/MovieCards'], resolve);
const AddMovie = (resolve) => require(['./components/AddMovie'], resolve);
const MoviePage = (resolve) => require(['./components/MoviePage/MoviePage'], resolve);
const MovieEdit = (resolve) => require(['./components/MoviePage/Edit'], resolve);
const MovieView = (resolve) => require(['./components/MoviePage/View'], resolve);

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path: '/',
            component: MovieCards
        },
        {
            path: '/foo',
            component: Foo
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
        }
    ]
});