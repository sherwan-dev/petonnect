import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['type', 'subtype'];

    connect() {
        this.resetSubtype(); 
    }

    async onTypeChange() {
        const typeId = this.typeTarget.value;

        this.resetSubtype();
        console.log(typeId);

        if (!typeId) {
            return;
        }

        try {
            const response = await fetch(`/api/petType/${typeId}`);
            const data = await response.json();
            this.populateSubtype(data.subtypes);
        } catch (error) {
            console.error('Failed to load subtypes', error);
        }
    }

    resetSubtype() { 
        this.subtypeTarget.innerHTML = '';
        this.subtypeTarget.disabled = true;

        const placeholder = document.createElement('option');
        placeholder.textContent = 'Select*';
        placeholder.value = ''; 
        this.subtypeTarget.appendChild(placeholder);
    }

    populateSubtype(subtypes) {
        subtypes.forEach(subtype => {
            const option = document.createElement('option');
            option.value = subtype.id;
            option.textContent = subtype.name;
            this.subtypeTarget.appendChild(option);
        });

        this.subtypeTarget.disabled = false;
    }
}
