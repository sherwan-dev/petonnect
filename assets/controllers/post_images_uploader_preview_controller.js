import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["input", "previewContainer"];

    connect() {}

    browse() {
        this.inputTarget.click();
    }

    update() {
        const input = this.inputTarget;
        const container = this.previewContainerTarget;

        container.innerHTML = '';

        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-lg border border-gray-200';
                    container.appendChild(img);
                };

                reader.readAsDataURL(file);
            });
        }
    }
}