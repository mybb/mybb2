import Cookie from "./cookie";
import Spinner from "./spinner";
import Post from "./post";

(<any>window).mybb = {
    Lang: (<any>window).Lang || {}, // TODO: Make an ES6 module
    cookie: new Cookie(),
    spinner: new Spinner(),
    post: new Post()
};
