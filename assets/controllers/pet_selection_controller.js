import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    async select() {
        const url = this.element.dataset.actionUrl;
        const pets = document.querySelectorAll('.pets-container .pet-item');
        
        pets.forEach((pet) => {
            const isTarget = pet.dataset.petId === this.element.dataset.petId;
            const imageContainer = pet.querySelector('.pet-image-container');
            
            pet.classList.toggle('active', isTarget);
            imageContainer?.classList.toggle('ring-3', isTarget);
            imageContainer?.classList.toggle('ring-(--green-600)', isTarget);
        });

        let response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "text/vnd.turbo-stream.html",
            },
        });

        if (response.ok) {
            const html = await response.text();
            Turbo.renderStreamMessage(html);
        }
    }
}
