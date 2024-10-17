import { abbreviate } from "./abbreviate";
//import { particlesEffect } from "./particles-effect";

const confirmButton = (id, isConfirm, count, isAuthenticated, typeVote) => ({
    id,
    isConfirm,
    count,
    isAuthenticated,
    typeVote,
    confirmButtonText: '',

    init() {
        this.setText();
        this.initEventListeners();
    },

    setText() {
        this.confirmButtonText = this.count === 0 ? '' : abbreviate(this.count);
    },

    toggleConfirm(e) {

        if (!this.isAuthenticated) {
            window.Livewire.navigate('/login');
            return;
        }

        if (this.isConfirm) {
            this.$wire.unlike(id);
            this.$dispatch('link.remove_confirm', { id: id });
        } else {
            this.$wire.like(id);
            this.$dispatch('link.add_confirm', { id: id });
        }
    },

    initEventListeners() {
        window.addEventListener('link.add_confirm', (event) => {
            if (event.detail.id == this.id) {
                this.isConfirm = true;
                this.count++;
                this.setText();
            }
        });

        window.addEventListener('link.remove_confirm', (event) => {
            if (event.detail.id == this.id) {
                this.isConfirm = false;
                this.count--;
                this.setText();
            }
        });
    }
});

export { confirmButton };
