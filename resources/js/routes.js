
import Login from './components/login'
import NotFound from "./components/NotFound";
import albums_library from "./components/albums_library";
import album_content from "./components/album_content";
import album_upload from "./components/album_upload";
import song_upload from "./components/song_upload";
import profile from "./components/profile/profile";
import signup from "./components/signup";
import add_artist from "./components/add_artist"




export default {
    mode: 'history',

    linkActiveClass: 'font-weight-bold',

    routes: [
        {
            path: '*',
            component: NotFound
        },

        {
            path: '/',
            component: albums_library,
            name:'albums_library'
        },

        {
            path: '/Login',
            component: Login
        },
        {
            path: '/signup',
            component: signup
        },
        {
            path: '/add_album',
            component: album_upload,
        },
        {
            path: '/add_song',
            component: song_upload,
        },
        {
            path: '/album/:albumId',
            component: album_content,
        },
        {
            path: '/profile',
            component: profile,
        },
        {
            path: '/add_artist',
            component: add_artist,
        },


    ]
};
