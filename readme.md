## About Order Management System(OMS)

OMS is a web application developed on Vue JS(2.0+) and Laravel framework(5.3+).

- Front-end UI uses Vue JS.
- Backend uses Laravel framework.


## How to install..

1. Open your terminal
2. Clone the repo:

        git clone https://github.com/manojkmishra/oms.git     
      

3. Once finished, change directory to oms:

        cd oms

4. Install all laravel dependencies:

        composer install

5. Copy .env file:

        cp .env.example .env

6. Modify environment variables accordingly

7. Generate application key:

        php artisan key:generate

8. Install frontend dependencies:

        npm install

9. Copy and replace the following source from repo again as step 4 and 8 will replace
the following open source which were modified to meet our needs.

- oms/node_modules/nprogress/nprogress.css
- oms/node_modules/vue-strap/src/utils/PolyFills.js
- oms/node_modules/vuetable-2/src/components/Vuetable.vue
- oms/vendor/simplesoftwareio/simple-sms/src/Drivers/EmailSMS.php

        git checkout node_modules/nprogress/nprogress.css
        git checkout node_modules/vue-strap/src/utils/PolyFills.js
        git checkout node_modules/vuetable-2/src/components/Vuetable.vue
        git checkout vendor/simplesoftwareio/simple-sms/src/Drivers/EmailSMS.php

10. Uninstall and re-install vue-template-compiler to meet requirement for vue@2.2.6
        
        npm uninstall vue-template-compiler
        npm install vue-template-compiler@2.2.6
        
11. Compile frontend source in production mode:

        npm run production


