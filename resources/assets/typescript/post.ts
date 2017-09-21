import Utils from "./utils";

const utils = new Utils(),
      Lang = (<any>window).Lang || {};

export default class Post {
    /**
     * Create control functionality for posts
     * 
     * @param postToggles Elements used to toggle hiding/showing a post
     * @param postDeletes Elements used to delete a post
     */
    constructor(protected postToggles: NodeListOf<Element> = document.querySelectorAll('.post__toggle'),
                protected postDeletes: NodeListOf<Element> = document.querySelectorAll('.delete a')) {
        const _this = this;

        if (postToggles.length > 0) {
            utils.forEach(postToggles, (i, toggle) => {
                toggle.addEventListener('click', _this.togglePost);
            }, this);
        }
        
        if (postDeletes.length > 0) {
            utils.forEach(postDeletes, (i, toggle) => {
                toggle.addEventListener('click', _this.togglePost);
            }, this);
        }
    }
    
    /**
     * Toggle the current state of a post
     *
     * @param event Event of clicked item, used to determine the post
     * @return {string} Current state of thread (hidden or visible)
     */
    public togglePost(event: Event): string {
        event.preventDefault();
        let state: string = 'visible';
        const currentPost = (<Element>event.target).closest('.post'),
              postId = currentPost.getAttribute('data-post-id');

        // Are we minimized or not?
        if (currentPost.classList.contains('post--hidden')) {
            currentPost.classList.remove('post--hidden');
            state = 'hidden';
        } else {
            currentPost.classList.add('post--hidden');
        }
        
        return state;
    }
    
    /**
     * Show confirmation dialog for delete
     *
     * @param event Event of clicked item
     * @return {boolean} true if user confirms, false otherwise
     */
    public confirmDelete(event: Event): boolean {
        return confirm(Lang.get('topic.confirmDelete'));
    }
}
