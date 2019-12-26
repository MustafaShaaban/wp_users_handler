// import 3d party modules
import $ from 'jquery';
import M from 'materialize-css';

// import plugin modules
import Config from './config';

class UICtr {

    constructor() {
        this.uiElements = {
            main: {
              tabs: $('.tabs')
            },
            welcome: {

            },
            configuration: {

            },
            documentation: {

            },
            store: {

            },
            contactUs: {

            }
        };
        this.prepare();
    }

    prepare() {
        // Init Hero tabs.
        M.Tabs.init(this.uiElements.main.tabs);
    }

    notices() {}
}

export default UICtr;