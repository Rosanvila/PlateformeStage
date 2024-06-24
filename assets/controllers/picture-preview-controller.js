import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    connect() {
        document.addEventListener('picture:changed', (event) => this.updatePicturePreview(event.detail.base64));
    }

    updatePicturePreview(base64) {
        document.getElementById("picturePreview").src = "data:image;base64,"+base64;
    }
}
