import { startStimulusApp } from '@symfony/stimulus-bundle';
import LiveController from './controllers/live_controller';


const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register('live', LiveController);
