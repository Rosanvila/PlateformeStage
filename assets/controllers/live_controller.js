import { Controller } from '@hotwired/stimulus';
import { useLiveComponent } from '@symfony/ux-live-component';

export default class extends Controller {
    connect() {
        useLiveComponent(this);
    }
}
