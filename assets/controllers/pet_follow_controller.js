import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String,
        followerId: Number,
        following: Boolean
    };

    static classes = ['follow', 'following'];

    connect() {
        this.loading = false;
        this.render();
    }

    async toggle(event) {
        event.preventDefault();

        if (this.loading || !this.hasUrlValue || !this.hasFollowerIdValue) {
            return;
        }

        this.loading = true;
        this.element.disabled = true;

        const method = this.followingValue ? 'DELETE' : 'POST';

        try {
            const response = await fetch(this.urlValue, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    follower_id: this.followerIdValue
                })
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(payload.error || payload.message || 'Request failed');
            }

            this.followingValue = method === 'POST';
            this.render();
        } catch (error) {
            console.error(error);
            this.element.title = error.message;
        } finally {
            this.loading = false;
            this.element.disabled = false;
        }
    }

    render() {
        this.element.textContent = this.followingValue ? 'UNFOLLOW' : 'FOLLOW';

        if (this.hasFollowClass) {
            this.element.classList.remove(...this.followClass.split(' '));
        }
        if (this.hasFollowingClass) {
            this.element.classList.remove(...this.followingClass.split(' '));
        }

        if (this.followingValue && this.hasFollowingClass) {
            this.element.classList.add(...this.followingClass.split(' '));
            return;
        }

        if (this.hasFollowClass) {
            this.element.classList.add(...this.followClass.split(' '));
        }
    }
}
