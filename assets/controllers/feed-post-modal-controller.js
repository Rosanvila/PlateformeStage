import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    modal = null;

    connect() {
        this.modal = Modal.getOrCreateInstance(this.element);
        document.addEventListener('modal:close', () => this.modal.hide());
    }
}
