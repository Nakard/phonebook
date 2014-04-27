# Phone book module for ZF1

Module is a standalone phone book that contains persons and associated phone numbers.

## Installation

1. Clone repo in your Zend application modules

        cd /path/to/your/zend/app
        git clone https://github.com/Nakard/phonebook.git

2. Install module dependencies using Composer. If you don't have it, here's the link where you can fetch it
https://getcomposer.org/download/. To install dependencies run

        composer install

in the phonebook module root.

3. Add those two lines to your application/configs/application.ini file

        resources.frontController.moduleDirectory = APPLICATION_PATH "/modules"
        resources.modules[] = ""

4. Make sure your application/cache file is writable and exists

5. Symlink phonebook view assets in your public assets dir

The module should be running now.


### Notice

For the time being the module has its own layout based on bootstrap, so don't be surprised.