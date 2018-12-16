###Add2Ontology

---
### Requires
 * Composer: https://getcomposer.org/download/
 * Php
 * Node.js
 * Python 2.7 as python2 
 * MySQL
 * VCBuilder.exe (part of Visual C++ development environment)
  
### How to use

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__ [a new app needs a new APP Key in the .env file. This command should automatically insert a .env APP_KEY= in the .env file]
- Run __php artisan migrate__
- Run __npm install__ or __npm install -g --production windows-build-tools__ when VCBuilder.exe is not already installed
- Run __npm run dev__
- That's it - load the homepage, use __Register__ link and use the CRUD

---

### License

Please use and re-use however you want.
