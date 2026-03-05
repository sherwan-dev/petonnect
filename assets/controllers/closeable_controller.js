import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        autoClose: Number,
    };
    connect() {
        if (this.autoCloseValue) {
            setTimeout(() => {
                this.close();
            }, this.autoCloseValue);
        }
    }
    close() {
        this.element.remove();
    }
}
