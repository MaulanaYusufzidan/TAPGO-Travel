import './bootstrap';

// One Bootstrap 5 entrypoint for collapse, dropdown, and modal data APIs.
// Importing bootstrap.bundle as well would attach duplicate event handlers.
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;
