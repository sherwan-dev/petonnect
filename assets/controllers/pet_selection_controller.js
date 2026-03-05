import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('PetSelectionController connected');
    }

async select() {
    const url = this.element.dataset.actionUrl;

    let response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'text/vnd.turbo-stream.html' 
        }
    });

    if (response.ok) {
        const html = await response.text();
        Turbo.renderStreamMessage(html); 
    }
}

}
