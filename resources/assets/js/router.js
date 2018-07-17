import VueRouter    from 'vue-router'

//Define route components
const Foo = { template: '<div>Foo</div>' };

// lazy load components
const MovieCards = (resolve) => require(['./components/MovieCards'], resolve);
const MoviePage = (resolve) => require(['./components/MoviePage'], resolve);
const AddMovie = (resolve) => require(['./components/AddMovie'], resolve);

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { path: '/', component: MovieCards },
        { path: '/foo', component: Foo },
        { path: '/addMovie', component: AddMovie },
        { path: '/movie/:id', component: MoviePage, props: true },
    ]
});