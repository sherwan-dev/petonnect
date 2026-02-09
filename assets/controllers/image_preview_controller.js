import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["input", "preview", "placeholder", "filename"];
    connect() {}

    update() {
        const input = this.inputTarget;
        const file = input.files[0];

        if (file) {
            let displayName = file.name;
            
            this.filenameTarget.textContent = displayName.length > 15 ? displayName.substring(0, 15) + "..." : displayName;
            this.filenameTarget.classList.add(
                "text-(--green-300)",
                "font-semibold",
            );

            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewTarget.src = e.target.result;
                this.previewTarget.classList.remove("hidden");
                this.placeholderTarget.classList.add("hidden");
            };
            reader.readAsDataURL(file);
        }
    }
}
